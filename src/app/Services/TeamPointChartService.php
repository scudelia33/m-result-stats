<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Enums\Date;
use App\Models\CarriedOverPoint;
use App\Models\MatchInformation;
use App\Models\MatchResult;
use App\Models\MatchSchedule;
use App\Models\Season;
use App\Models\Team;
use App\Models\MatchCategory;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TeamPointChartService
{
    use CommonFunctionsTrait;
    use MstatsFunctionsTrait;

    /**
     * チームポイントチャート表示に必要なデータを準備します。
     *
     * - クエリパラメータのデフォルトを確保
     * - マスタデータ（seasons, matchCategories）を追加
     * - 対象試合日、チーム毎の持ち越しポイントと試合ポイントを取得し、グラフ用の累計ポイント配列を作成
     *
     * @param Request $request
     * @return Request
     */
    public function prepareIndexData(Request $request): Request
    {
        // クエリパラメータのデフォルトを確保
        $this->addQueryParameter($request, [
            'season_id' => BlankInList::EXIST->value,
            'match_category_id' => BlankInList::EXIST->value,
        ]);

        // マスタの取得
        $request->merge([
            'startDateForGraph' => Date::START_FOR_GRAPH->value,
            'seasons' => Season::get(),
            'matchCategories' => MatchCategory::get(),
        ]);

        // 対象試合日を取得して配列化
        $targetMatchDates = (function () use ($request) {
            $result = MatchSchedule::select('match_date')
            ->equalSeasonId($request->season_id)
            ->equalMatchCategoryId($request->match_category_id)
            ->whereHas('matchInformation.matchResult', function ($query) {
                $query->where('player_id', '<>', 0);
            })
            ->get()
            ->toArray();

            return Arr::flatten(collect([Date::START_FOR_GRAPH->value])->merge($result)->toArray());
        })();

        // チームID/試合日毎のポイントを取得
        $teamPointsPerMatchDate = (function () use ($request) {
            $playerAffiliation = $this->getDefinitionOfPlayerAffiliation($request->season_id);

            $matchInformation = MatchInformation::select('match_id as match_id_mi', 'match_date');

            $carriedOverPoints = CarriedOverPoint::with(['team:team_id,team_name_shortened,graph_color'])
            ->select('team_id', 'carried_over_point as sum_point')
            ->selectRaw('? as match_date', [Date::START_FOR_GRAPH->value])
            ->equalSeasonId($request->season_id)
            ->equalMatchCategoryId($request->match_category_id)
            ->orderBy('team_id')
            ->get()
            ->toArray();

            $matchResults = MatchResult::select('team_id', 'match_date')
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
            ->groupBy('match_date')
            ->orderBy('team_id')
            ->orderBy('match_date')
            ->get()
            ->toArray();

            return collect($carriedOverPoints)->merge($matchResults)->groupBy('team_id');
        })();

        // Chart.js 用にチーム情報と累計ポイント配列を作成
        $teamPoints = (function () use ($teamPointsPerMatchDate, $targetMatchDates) {
            $results = [];
            foreach ($teamPointsPerMatchDate as $key => $teamPointPerMatchDate) {
                $teamPointsArray = Arr::mapWithKeys($teamPointPerMatchDate->toArray(), function (array $items, int $key) {
                    return [$items['match_date'] => $items['sum_point']];
                });

                $teamPointsPerMatchDateForGraph = (function () use ($teamPointsArray, $targetMatchDates) {
                    $added = $teamPointsArray;
                    foreach ($targetMatchDates as $key => $value) {
                        $added = Arr::add($added, $value, '0');
                    }
                    $sorted = collect($added)->sortKeysUsing('strnatcasecmp');
                    $totalPoint = '';
                    foreach ($sorted as $key => $value) {
                        $totalPoint = bcadd($totalPoint, $value, 1);
                        $sorted[$key] = $totalPoint;
                    }
                    return $sorted;
                })();

                [$dates, $points] = Arr::divide($teamPointsPerMatchDateForGraph->toArray());
                $results[] = [
                    'team_id' => $key,
                    'team_name' => data_get($teamPointPerMatchDate, '0.team.team_name_shortened'),
                    'team_color' => data_get($teamPointPerMatchDate, '0.team.graph_color'),
                    'points' => $points,
                ];
            }
            return $results;
        })();

        $request->merge([
            'teamPoints' => $teamPoints,
            'targetMatchDates' => $targetMatchDates,
        ]);

        return $request;
    }
}
