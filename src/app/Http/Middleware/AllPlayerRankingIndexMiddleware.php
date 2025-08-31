<?php

namespace App\Http\Middleware;

use App\Enums\BlankInList;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Models\Season;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllPlayerRankingIndexMiddleware
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
        ]);

        // マスタの取得
        $request->merge([
            'seasons' => Season::get(),
            'matchCategories' => MatchCategory::get(),
        ]);

        // チームIDでグルーピングするために、結合用の成績所属テーブルの定義
        $playerAffiliation = $this->getDefinitionOfPlayerAffiliation($request->season_id);

        // オール選手ランキングの取得
        $allPlayerRankings = MatchResult::with([
            'player',
            'playerAffiliation' => function (HasOne $query) use ($request) {
                $query->equalSeasonId($request->season_id);
            },
            'playerAffiliation.team',
        ])
        ->select(
            'player_id',
        )
        ->selectRaw(
            'RANK() OVER (ORDER BY SUM(point + IFNULL(penalty, 0)) DESC) as player_rank', // 選手順位
        )
        ->selectRaw(
            'SUM(point + IFNULL(penalty, 0)) as sum_point', // ポイント
        )
        ->selectRaw(
            'COUNT(`rank`) as match_count', // 試合数
        )
        ->when(true, function (Builder $query) {
            // 順位1-4を取得するSQLを生成
            $this->generationSqlOfRank($query);
        })
        ->joinSub($playerAffiliation, 'pa', function (JoinClause $join) {
            $join->on('player_id', '=', 'pa.player_id_pa');
        })
        ->whereHas('matchInformation.matchSchedule', function (Builder $query) use ($request) {
            $query->equalSeasonId($request->season_id); // シーズンでの絞り込み
            $query->equalMatchCategoryId($request->match_category_id); // 試合カテゴリーでの絞り込み
        })
        ->groupBy('player_id')
        ->orderBy('sum_point', 'desc')
        ->get()
        ;

        $request->merge([
            'allPlayerRankings' => $allPlayerRankings,
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
