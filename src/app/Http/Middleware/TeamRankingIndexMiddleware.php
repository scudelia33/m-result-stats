<?php

namespace App\Http\Middleware;

use App\Enums\BlankInList;
use App\Enums\CheckBox;
use App\Models\CarriedOverPoint;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Models\PlayerAffiliation;
use App\Models\QualifyingLine;
use App\Models\Season;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamRankingIndexMiddleware
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
            'season_id' => BlankInList::EXIST->value,
            'match_category_id' => BlankInList::EXIST->value,
            'is_combine_carried_over_point' => CheckBox::ON->value,
        ]);

        // マスタの取得
        $request->merge([
            'seasons' => Season::get(),
            'matchCategories' => MatchCategory::get(),
            'qualifyingLine' => QualifyingLine::select('*')
                ->equalSeasonId($request->season_id)
                ->equalMatchCategoryId($request->match_category_id)
                ->first()
            ,
        ]);

        // カテゴリ内チームポイントの取得
        $teamPointInCategory = (function () use ($request) {
            // チームIDでグルーピングするために、結合用の成績所属テーブルの定義
            $playerAffiliation = PlayerAffiliation::select(
                'player_id as player_id_pa',
                'team_id',
                'season_id',
            )
            ->equalSeasonId($request->season_id)
            ;

            // サブクエリー用のチームランキング
            $teamRankings = MatchResult::select(
                'team_id as team_id_tr',
            )
            ->selectRaw(
                'SUM(point + IFNULL(penalty, 0)) as point_in_category', // カテゴリ内のポイント
            )
            ->selectRaw(
                'COUNT(`rank`) as match_count', // 試合数
            )
            ->when(true, function (Builder $query) {
                // 順位1-4を取得するSQLを生成
                for ($i = 1; $i < 5; $i++) {
                    $column = "COUNT(CASE WHEN `rank` = {$i} THEN `rank` ELSE null END) AS rank{$i}";
                    $query->selectRaw($column);
                }
            })
            ->joinSub($playerAffiliation, 'pa', function (JoinClause $join) {
                $join->on('player_id', '=', 'pa.player_id_pa');
            })
            ->whereHas('matchInformation.matchSchedule', function (Builder $query) use ($request) {
                $query->equalSeasonId($request->season_id); // シーズンでの絞り込み
                $query->equalMatchCategoryId($request->match_category_id); // 試合カテゴリーでの絞り込み
            })
            ->groupBy('team_id')
            ->orderBy('point_in_category', 'desc')
            ;
            return $teamRankings;
        })();

        // 持ち越しポイントテーブル
        $teamRankings = CarriedOverPoint::with([
            'team'
        ])
        ->select(
            'team_id',
            'point_in_category',
            'match_count',
            'rank1',
            'rank2',
            'rank3',
            'rank4',
        )
        ->when($request->is_combine_carried_over_point, function (Builder $query) { // 持ち越しポイント
            $query->selectRaw('carried_over_point');
        }, function (Builder $query) {
            $query->selectRaw('0 as carried_over_point');
        })
        ->when($request->is_combine_carried_over_point, function (Builder $query) { // 合計ポイント
            $query->selectRaw('carried_over_point + point_in_category as sum_point');
        }, function (Builder $query) {
            $query->selectRaw('0 + point_in_category as sum_point');
        })
        ->when($request->is_combine_carried_over_point, function (Builder $query) { // チーム順位
            $query->selectRaw('rank() OVER (ORDER BY carried_over_point + point_in_category DESC) AS team_rank');
        }, function (Builder $query) {
            $query->selectRaw('rank() OVER (ORDER BY 0 + point_in_category DESC) AS team_rank');
        })
        ->joinSub($teamPointInCategory, 'tr', function (JoinClause $join) { // カテゴリ内チームポイントとの結合
            $join->on('team_id', '=', 'tr.team_id_tr');
        })
        ->equalSeasonId($request->season_id)
        ->equalMatchCategoryId($request->match_category_id)
        ->orderBy('sum_point', 'desc')
        ->get()
        ;

        $request->merge([
            'teamRankings' => $teamRankings,
            'matchLastDateDisplay' => $this->getMatchLastDateDisplay(
                $request->season_id,
                $request->match_category_id
            ),
        ]);

        return $next($request);
        // ====================
        // ここに後処理を記述
        // ====================
    }
}
