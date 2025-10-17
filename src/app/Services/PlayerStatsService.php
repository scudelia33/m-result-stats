<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Models\Player;
use App\Models\Season;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PlayerStatsService
{
    use CommonFunctionsTrait;
    use MstatsFunctionsTrait;

    /**
     * 選手成績表示に必要なデータを準備します。
     *
     * - クエリパラメータのデフォルトを確保
     * - マスタデータ（seasons, matchCategories, players）を追加
     * - 選手のシーズン毎の詳細成績を集計
     *
     * @param Request $request
     * @return Request
     */
    public function prepareIndexData(Request $request): Request
    {
        // クエリパラメータのデフォルトを確保
        $this->addQueryParameter($request, [
            'match_category_id' => BlankInList::EXIST->value,
            'player_id' => BlankInList::EXIST->value,
        ]);

        // マスタデータの取得
        $request->merge([
            'seasons' => Season::get(),
            'matchCategories' => MatchCategory::get(),
            'players' => Player::orderBy('player_last_name_kana')
                ->orderBy('player_first_name_kana')
                ->get(),
        ]);

        // 選手の成績データを集計
        $playerStats = MatchResult::with(['player'])
            ->selectRaw('match_results.player_id')
            ->selectRaw('match_schedules.season_id')
            ->selectRaw('SUM(match_results.point + IFNULL(match_results.penalty, 0)) as sum_point')
            ->selectRaw('COUNT(match_results.`rank`) as match_count')
            ->when(true, function (Builder $query) {
                // 順位1-4を取得するSQLを生成
                for ($i = 1; $i < 5; $i++) {
                    $column = "COUNT(CASE WHEN match_results.`rank` = {$i} THEN match_results.`rank` ELSE null END) AS rank{$i}";
                    $query->selectRaw($column);
                }
            })
            ->join('match_information', 'match_results.match_id', '=', 'match_information.match_id')
            ->join('match_schedules', 'match_information.match_date', '=', 'match_schedules.match_date')
            ->when($request->player_id != BlankInList::EXIST->value, function (Builder $query) use ($request) {
                $query->equalPlayerId($request->player_id);
            })
            ->where('match_schedules.match_category_id', $request->match_category_id)
            ->groupBy('match_results.player_id', 'match_schedules.season_id')
            ->orderBy('match_results.player_id')
            ->orderBy('match_schedules.season_id', 'desc')
            ->get();

        $request->merge(['playerStats' => $playerStats]);

        return $request;
    }
}
