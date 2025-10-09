<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Enums\Date;
use App\Models\CarriedOverPoint;
use App\Models\MatchInformation;
use App\Models\MatchResult;
use App\Models\MatchCategory;
use App\Models\MatchSchedule;
use App\Models\Season;
use App\Models\Team;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TeamStatsService
{
    use CommonFunctionsTrait;
    use MstatsFunctionsTrait;

    /**
     * チームスタッツ表示に必要なデータを準備します。
     *
     * - クエリパラメータのデフォルトを確保
     * - マスタデータ（seasons, matchCategories, teams 等）を追加
     * - チームごとの試合日毎のポイント、持ち越しポイント、欠試合日の補完、通算ポイント、順位を計算
     *
     * @param Request $request
     * @return Request
     */
    public function prepareIndexData(Request $request): Request
    {
        // クエリパラメータのデフォルトを確保
        // デフォルト値は「全件」や「未選択」を意味する BlankInList::EXIST->value を利用し、初期表示時に全データを対象とするため
        $this->addQueryParameter($request, [
            'team_id' => BlankInList::EXIST->value,
            'season_id' => BlankInList::EXIST->value,
            'match_category_id' => BlankInList::EXIST->value,
        ]);

        // マスタデータの取得
        $request->merge([
            'startDateForGraph' => Date::START_FOR_GRAPH->value,
            'seasons' => Season::get(),
            'matchCategories' => MatchCategory::get(),
            'teams' => Team::get(),
            'teamCount' => CarriedOverPoint::select('team_id')
                ->equalSeasonId($request->season_id)
                ->equalMatchCategoryId($request->match_category_id)
                ->distinct()
                ->count('team_id'),
            'teamName' => optional(
                Team::select()
                    ->equalTeamId($request->team_id)
                    ->first()
            )->team_name ?? '',
        ]);

        // 1. チームID/試合日毎のポイント取得
        $teamPointsPerMatchDate = (function () use ($request) {
            $playerAffiliation = $this->getDefinitionOfPlayerAffiliation($request->season_id);

            $matchInformation = MatchInformation::select('match_id as match_id_mi', 'match_date');

            $result = MatchResult::select('team_id', 'match_date')
            ->selectRaw('SUM(point + IFNULL(penalty, 0)) as sum_point')
            ->joinSub($playerAffiliation, 'pa', function (JoinClause $join) {
                $join->on('player_id', '=', 'pa.player_id_pa');
            })
            ->joinSub($matchInformation, 'mi', function (JoinClause $join) {
                $join->on('match_id', '=', 'mi.match_id_mi');
            })
            ->whereHas('matchInformation.matchSchedule', function ($query) use ($request) {
                $query->equalSeasonId($request->season_id);
                $query->equalMatchCategoryId($request->match_category_id);
            })
            ->groupBy('team_id')
            ->groupBy('match_date');

            return $result;
        })();

        // 2. チームID毎の持ち越しポイント取得
        $carriedOverPoints = (function () use ($request) {
            return CarriedOverPoint::select('team_id')
            ->selectRaw('? as match_date', [Date::START_FOR_GRAPH->value])
            ->selectRaw('carried_over_point as sum_point')
            ->equalSeasonId($request->season_id)
            ->equalMatchCategoryId($request->match_category_id);
        })();

        // 3. 試合していない日付のレコードを追加
        $noMatchDates = (function () use ($request) {
            $playerAffiliation = $this->getDefinitionOfPlayerAffiliation($request->season_id);

            $matchInformation = MatchInformation::select('match_id as match_id_mi', 'match_date');

            $pointInDates = MatchResult::select('team_id as team_id_pd')
            ->selectRaw('DATE_FORMAT(match_date, "%Y-%m-%d") as match_date_pd')
            ->joinSub($playerAffiliation, 'pa', function (JoinClause $join) {
                $join->on('player_id', '=', 'pa.player_id_pa');
            })
            ->joinSub($matchInformation, 'mi', function (JoinClause $join) {
                $join->on('match_id', '=', 'mi.match_id_mi');
            })
            ->whereHas('matchInformation.matchSchedule', function ($query) use ($request) {
                $query->equalSeasonId($request->season_id);
                $query->equalMatchCategoryId($request->match_category_id);
            })
            ->groupBy('team_id')
            ->groupBy('match_date');

            $cartesianProductTeamAndMatchDate = (function () use ($request) {
                $matchDates = MatchSchedule::select('match_date')
                ->equalSeasonId($request->season_id)
                ->equalMatchCategoryId($request->match_category_id)
                ->whereHas('matchInformation.matchResult', function ($query) {
                    $query->where('player_id', '<>', 0);
                });

                return Team::select('team_id as team_id_cp')
                ->selectRaw('DATE_FORMAT(match_date, "%Y-%m-%d") as match_date_cp')
                ->whereHas('carriedOverPoint', function ($query) use ($request) {
                    $query->equalSeasonId($request->season_id);
                    $query->equalMatchCategoryId($request->match_category_id);
                })
                ->crossJoinSub($matchDates, 'ms', function (JoinClause $join) {});
            })();

            return DB::query()
            ->select('team_id_cp', 'match_date_cp')
            ->selectRaw('0 as point')
            ->fromSub($cartesianProductTeamAndMatchDate, 'cp')
            ->leftJoinSub($pointInDates, 'pd', function (JoinClause $join) {
                $join
                ->on('team_id_cp', '=', 'team_id_pd')
                ->on('match_date_cp', '=', 'match_date_pd');
            })
            ->whereNull('team_id_pd');
        })();

        // チームID/試合日毎の通算ポイントと順位を取得
        $teamPointsWithRankAndSumPoint = (function () use ($teamPointsPerMatchDate, $carriedOverPoints, $noMatchDates) {
            $subQuery = DB::query()
            ->select('team_id', 'match_date', 'sum_point as point')
            ->fromSub($teamPointsPerMatchDate, 'tp')
            ->unionAll($carriedOverPoints)
            ->unionAll($noMatchDates);

            $sumPointsPerMatchDate = DB::query()
            ->select('team_id', 'match_date', 'point')
            ->selectRaw('SUM(point) OVER (PARTITION BY team_id ORDER BY match_date) as sum_points_per_match_date')
            ->fromSub($subQuery, 'sq');

            return DB::query()
            ->select('team_id', 'match_date', 'point', 'sum_points_per_match_date')
            ->selectRaw('RANK() OVER (PARTITION BY match_date ORDER BY sum_points_per_match_date DESC) AS rank_per_match_date')
            ->fromSub($sumPointsPerMatchDate, 'sppmd')
            ->orderBy('team_id')
            ->orderBy('match_date')
            ->get()
            ->toArray();
        })();

        // 指定チームでフィルタ
        $designatedTeams = Arr::where($teamPointsWithRankAndSumPoint, function ($value, $key) use ($request) {
            return $value->team_id == $request->team_id;
        });

        $request->merge([
            'matchDates' => collect($designatedTeams)->pluck('match_date')->toArray(),
            'sumPoints' => collect($designatedTeams)->pluck('sum_points_per_match_date')->toArray(),
            'ranks' => collect($designatedTeams)->pluck('rank_per_match_date')->toArray(),
        ]);

        return $request;
    }
}
