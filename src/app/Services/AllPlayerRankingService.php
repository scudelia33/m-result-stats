<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AllPlayerRankingService
{
    use CommonFunctionsTrait;
    use MstatsFunctionsTrait;

    /**
     * オール選手ランキング表示に必要なデータを準備します。
     *
     * - クエリパラメータのデフォルトを確保
     * - マスタデータ（matchCategories）を追加
     * - 全選手の合計ポイント等を集計してランキングを作成
     *
     * @param Request $request
     * @return Request
     */
    public function prepareIndexData(Request $request): Request
    {
        $this->addQueryParameter($request, [
            'match_category_id' => BlankInList::EXIST->value,
        ]);

        $request->merge([
            'matchCategories' => MatchCategory::get(),
        ]);

        $allPlayerRankings = MatchResult::with(['player'])
        ->select('player_id')
        ->selectRaw('RANK() OVER (ORDER BY SUM(point + IFNULL(penalty, 0)) DESC) as player_rank')
        ->selectRaw('SUM(point + IFNULL(penalty, 0)) as sum_point')
        ->selectRaw('COUNT(`rank`) as match_count')
        ->when(true, function (Builder $query) {
            $this->generationSqlOfRank($query);
        })
        ->whereHas('matchInformation.matchSchedule', function (Builder $query) use ($request) {
            $query->equalMatchCategoryId($request->match_category_id);
        })
        ->groupBy('player_id')
        ->orderBy('sum_point', 'desc')
        ->get();

        $request->merge(['allPlayerRankings' => $allPlayerRankings]);

        return $request;
    }
}
