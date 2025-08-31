<?php

namespace App\Http\Middleware;

use App\Enums\BlankInList;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Closure;
use Illuminate\Database\Eloquent\Builder;
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
            'match_category_id' => BlankInList::EXIST->value,
        ]);

        // マスタの取得
        $request->merge([
            'matchCategories' => MatchCategory::get(),
        ]);

        // オール選手ランキングの取得
        $allPlayerRankings = MatchResult::with([
            'player',
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
        ->whereHas('matchInformation.matchSchedule', function (Builder $query) use ($request) {
            $query->equalMatchCategoryId($request->match_category_id); // 試合カテゴリーでの絞り込み
        })
        ->groupBy('player_id')
        ->orderBy('sum_point', 'desc')
        ->get()
        ;

        $request->merge([
            'allPlayerRankings' => $allPlayerRankings,
        ]);

        return $next($request);
        // ====================
        // ここに後処理を記述
        // ====================
    }
}
