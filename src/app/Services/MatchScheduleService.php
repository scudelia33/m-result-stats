<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Models\MatchCategory;
use App\Models\MatchSchedule;
use App\Models\Season;
use App\Traits\CommonFunctionsTrait;
use Illuminate\Http\Request;

class MatchScheduleService
{
    use CommonFunctionsTrait;

    /**
     * 試合日程一覧表示に必要なデータを準備します。
     *
     * - クエリパラメータのデフォルト値を確保（検索結果が0件になるように -1 を指定する想定）
     * - マスタデータ（seasons, matchCategories）をリクエストに追加
     * - 該当する MatchSchedule コレクションを取得してリクエストにマージ
     *
     * @param Request $request
     * @return Request
     */
    public function prepareIndexData(Request $request): Request
    {
        // クエリパラメータのデフォルトを確保
        $this->addQueryParameter($request, [
            'season_id' => BlankInList::NON->value,
            'match_category_id' => BlankInList::NON->value,
        ]);

        // マスタデータを追加
        $request->merge([
            'seasons' => Season::get(),
            'matchCategories' => MatchCategory::get(),
        ]);

        // 試合日程を取得
        $matchSchedules = MatchSchedule::with([
            'season',
            'matchCategory',
        ])
        ->when($request->season_id, function ($query) use ($request) {
            $query->equalSeasonId($request->season_id);
        })
        ->when($request->match_category_id, function ($query) use ($request) {
            $query->equalMatchCategoryId($request->match_category_id);
        })
        ->get();

        $request->merge([
            'matchSchedules' => $matchSchedules,
        ]);

        return $request;
    }
}
