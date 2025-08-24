<?php

namespace App\Http\Middleware;

use App\Enums\BlankInList;
use App\Enums\Date;
use App\Models\CarriedOverPoint;
use App\Models\MatchCategory;
use App\Models\MatchInformation;
use App\Models\MatchResult;
use App\Models\MatchSchedule;
use App\Models\PlayerAffiliation;
use App\Models\Season;
use App\Models\Team;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TeamStatsIndexMiddleware
{
    use CommonFunctionsTrait;
    use MstatsFunctionsTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ====================
        // ここに前処理を記述
        // ====================
        // クエリパラメータが存在しない場合を考慮して、クエリパラメータの追加
        $this->addQueryParameter($request, [
            'team_id' => BlankInList::EXIST->value,
            'season_id' => BlankInList::EXIST->value,
            'match_category_id' => BlankInList::EXIST->value,
        ]);

        // マスタの取得
        $request->merge([
            'startDateForGraph' => Date::START_FOR_GRAPH->value,
            'seasons' => Season::get(),
            'matchCategories' => MatchCategory::get(),
            'teams' => Team::get(),
            // チャートのY軸用にチーム数を取得する
            'teamCount' => CarriedOverPoint::select()
                ->equalSeasonId($request->season_id)
                ->equalMatchCategoryId($request->match_category_id)
                ->count(),
            'teamName' => Team::select()
                ->equalTeamId($request->team_id)
                ->first()
                ->team_name ?? '',
        ]);

        // 1.チームID/試合日毎のポイントを取得
        $teamPointsPerMatchDate = (function () use ($request) {
            // チームIDでグルーピングするために、結合用の成績所属テーブルの定義
            $playerAffiliation = PlayerAffiliation::select(
                'player_id as player_id_pa',
                'team_id',
                'season_id',
            )
            ->equalSeasonId($request->season_id)
            ;

            // 試合日でグルーピングするために、結合用の試合情報テーブルの定義
            $matchInformation = MatchInformation::select(
                'match_id as match_id_mi',
                'match_date',
            )
            ;

            $result = MatchResult::select(
                'team_id',
                'match_date',
            )
            ->selectRaw(
                'SUM(point + IFNULL(penalty, 0)) as sum_point', // ポイント
            )
            ->joinSub($playerAffiliation, 'pa', function (JoinClause $join) {
                $join->on('player_id', '=', 'pa.player_id_pa');
            })
            ->joinSub($matchInformation, 'mi', function (JoinClause $join) {
                $join->on('match_id', '=', 'mi.match_id_mi');
            })
            ->whereHas('matchInformation.matchSchedule', function (Builder $query) use ($request) {
                $query->equalSeasonId($request->season_id); // シーズンでの絞り込み
                $query->equalMatchCategoryId($request->match_category_id); // 試合カテゴリーでの絞り込み
            })
            ->groupBy('team_id')
            ->groupBy('match_date')
            ;
            return $result;
        })();

        // 2.チームID毎の持ち越しポイントを取得
        $carriedOverPoints = (function () use ($request){
            return CarriedOverPoint::select(
                'team_id',
            )
            ->selectRaw(
                '? as match_date', [Date::START_FOR_GRAPH->value]
            )
            ->selectRaw(
                'carried_over_point as sum_point',
            )
            ->equalSeasonId($request->season_id) // シーズンでの絞り込み
            ->equalMatchCategoryId($request->match_category_id) // 試合カテゴリーでの絞り込み
            ;
        })();

        // 3.試合していない日付のレコードの追加
        $noMatchDates = (function () use ($request) {
            // チームIDでグルーピングするために、結合用の成績所属テーブルの定義
            $playerAffiliation = PlayerAffiliation::select(
                'player_id as player_id_pa',
                'team_id',
                'season_id',
            )
            ->equalSeasonId($request->season_id)
            ;

            // 試合日でグルーピングするために、結合用の試合情報テーブルの定義
            $matchInformation = MatchInformation::select(
                'match_id as match_id_mi',
                'match_date',
            )
            ;

            // 1.試合日があるレコード(チームID/試合日)の生成
            $pointInDates = MatchResult::select(
                'team_id as team_id_pd',
            )
            ->selectRaw('DATE_FORMAT(match_date, "%Y-%m-%d") as match_date_pd')
            ->joinSub($playerAffiliation, 'pa', function (JoinClause $join) {
                $join->on('player_id', '=', 'pa.player_id_pa');
            })
            ->joinSub($matchInformation, 'mi', function (JoinClause $join) {
                $join->on('match_id', '=', 'mi.match_id_mi');
            })
            ->whereHas('matchInformation.matchSchedule', function (Builder $query) use ($request) {
                $query->equalSeasonId($request->season_id); // シーズンでの絞り込み
                $query->equalMatchCategoryId($request->match_category_id); // 試合カテゴリーでの絞り込み
            })
            ->groupBy('team_id')
            ->groupBy('match_date')
            ;

            // 2.チームID/試合日のデカルト積のレコードの生成 ※試合日のないレコードも含まれる
            $cartesianProductTeamAndMatchDate = (function () use ($request) {
                // サブクエリ用試合日の一覧を生成
                $matchDates = MatchSchedule::select('match_date')
                ->equalSeasonId($request->season_id) // シーズンでの絞り込み
                ->equalMatchCategoryId($request->match_category_id) // 試合カテゴリーでの絞り込み
                ->whereHas('matchInformation.matchResult', function (Builder $query) {
                    // 試合が開催されている試合日を取得
                    // 試合が開催されていない場合は選手IDが0となる
                    $query->where('player_id', '<>', 0);
                })
                ;

                return Team::select(
                    'team_id as team_id_cp',
                )
                ->selectRaw('DATE_FORMAT(match_date, "%Y-%m-%d") as match_date_cp')
                ->whereHas('carriedOverPoint', function (Builder $query) use ($request) {
                    $query->equalSeasonId($request->season_id);
                    $query->equalMatchCategoryId($request->match_category_id);
                })
                ->crossJoinSub($matchDates, 'ms', function (JoinClause $join) {
                })
                ;
            })();

            // チーム/試合日のデカルト積から試合日のあるレコードを取り除く
            return DB::query()
            ->select(
                'team_id_cp',
                'match_date_cp',
            )
            ->selectRaw('0 as point')
            ->fromSub($cartesianProductTeamAndMatchDate, 'cp')
            ->leftJoinSub($pointInDates, 'pd', function (JoinClause $join) {
                $join
                ->on('team_id_cp', '=', 'team_id_pd')
                ->on('match_date_cp', '=', 'match_date_pd')
                ;
            })
            ->whereNull('team_id_pd')
            ;
        })();

        // チームID/試合日毎の通算ポイントと順位を取得する
        $teamPointsWithRankAndSumPoint = (function () use ($teamPointsPerMatchDate, $carriedOverPoints, $noMatchDates) {
            // TODO:順位の算出用にチームID/試合日毎のポイントのレコードを生成
            // サブクエリ:チームID/試合日毎のポイントを取得
            // UNION ALL:チームID毎の持ち越しポイントの追加
            // UNION ALL:試合していないレコードの追加
            $subQuery = DB::query()
            ->select(
                'team_id',
                'match_date',
                'sum_point as point',
            )
            ->fromSub($teamPointsPerMatchDate, 'tp')
            ->unionAll($carriedOverPoints)
            ->unionAll($noMatchDates)
            ;

            // チームID/試合日ごとの通算ポイントを追加
            $sumPointsPerMatchDate = DB::query()
            ->select(
                'team_id',
                'match_date',
                'point',
            )
            ->selectRaw( // 試合毎の通算ポイント
                'SUM(point) OVER (PARTITION BY team_id ORDER BY match_date) as sum_points_per_match_date'
            )
            ->fromSub($subQuery, 'sq')
            ;

            // チームID/試合日ごとの順位ポイントを追加
            return DB::query()
            ->select(
                'team_id',
                'match_date',
                'point',
                'sum_points_per_match_date',
            )
            ->selectRaw( // 試合毎の順位
                'RANK() OVER (PARTITION BY match_date ORDER BY sum_points_per_match_date DESC) AS rank_per_match_date'
            )
            ->fromSub($sumPointsPerMatchDate, 'sppmd')
            ->orderBy('team_id')
            ->orderBy('match_date')
            ->get()
            ->toArray()
            ;
        })();

        // 指定チームでフィルタリングする
        $designatedTeams = Arr::where($teamPointsWithRankAndSumPoint, function ($value, $key) use ($request) {
            return $value->team_id == $request->team_id;
        });

        // 試合日・通算ポイント・試合日時点の順位を渡す
        $request->merge([
            'matchDates' => collect($designatedTeams)->pluck('match_date')->toArray(),
            'sumPoints' => collect($designatedTeams)->pluck('sum_points_per_match_date')->toArray(),
            'ranks' => collect($designatedTeams)->pluck('rank_per_match_date')->toArray(),
        ]);

        return $next($request);
        // ====================
        // ここに後処理を記述
        // ====================
    }
}
