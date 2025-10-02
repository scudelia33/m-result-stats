<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Models\Season;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class SeasonPlayerRankingService
{
    use CommonFunctionsTrait;
    use MstatsFunctionsTrait;

    /**
     * シーズン選手ランキング表示に必要なデータを準備します。
     *
     * - クエリパラメータのデフォルトを確保
     * - マスタデータ（seasons, matchCategories）を追加
     * - プレイヤーごとの合計ポイント等を集計してランキングを作成
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
            'seasons' => Season::get(),
            'matchCategories' => MatchCategory::get(),
        ]);

        // チームIDでグルーピングするために、結合用の成績所属テーブルの定義
        $playerAffiliation = $this->getDefinitionOfPlayerAffiliation($request->season_id);

        // シーズン選手ランキングを取得
        $seasonPlayerRankings = MatchResult::with([
            'player',
            'playerAffiliation' => function (HasOne $query) use ($request) {
                $query->equalSeasonId($request->season_id);
            },
            'playerAffiliation.team',
        ])
        ->select('player_id')
        ->selectRaw('RANK() OVER (ORDER BY SUM(point + IFNULL(penalty, 0)) DESC) as player_rank')
        ->selectRaw('SUM(point + IFNULL(penalty, 0)) as sum_point')
        ->selectRaw('COUNT(`rank`) as match_count')
        ->when(true, function ($query) {
            // 順位1-4を取得する SQL を生成
            $this->generationSqlOfRank($query);
        })
        ->joinSub($playerAffiliation, 'pa', function (JoinClause $join) {
            $join->on('player_id', '=', 'pa.player_id_pa');
        })
        ->whereHas('matchInformation.matchSchedule', function ($query) use ($request) {
            $query->equalSeasonId($request->season_id);
            $query->equalMatchCategoryId($request->match_category_id);
        })
        ->groupBy('player_id')
        ->orderBy('sum_point', 'desc')
        ->get();

        $request->merge([
            'seasonPlayerRankings' => $seasonPlayerRankings,
            'matchLastDateDisplay' => $this->getMatchLastDateDisplay(
                $request->season_id,
                $request->match_category_id
            ),
        ]);

        return $request;
    }
}
