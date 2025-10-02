<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Enums\CheckBox;
use App\Models\CarriedOverPoint;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Models\QualifyingLine;
use App\Models\Season;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;

class TeamRankingService
{
    use CommonFunctionsTrait;
    use MstatsFunctionsTrait;

    /**
     * チームランキング一覧表示のためのデータを準備します。
     *
     * - クエリパラメータのデフォルト値を確保
     * - マスタデータ（seasons, matchCategories, qualifyingLine）を追加
     * - カテゴリ内のチームポイントと持ち越しポイントを結合してチームランキングを作成
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

        // カテゴリ内チームポイントのサブクエリ定義
        $teamPointInCategory = (function () use ($request) {
            // チームでグルーピングするための成績所属テーブル定義を作成
            $playerAffiliation = $this->getDefinitionOfPlayerAffiliation($request->season_id);

            $teamRankings = MatchResult::select(
                'team_id as team_id_tr',
            )
            ->selectRaw('SUM(point + IFNULL(penalty, 0)) as point_in_category')
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
            ->groupBy('team_id')
            ->orderBy('point_in_category', 'desc');

            return $teamRankings;
        })();

        // 持ち越しポイントとカテゴリ内ポイントを結合して最終的なランキングを取得
        $teamRankings = CarriedOverPoint::with(['team'])
        ->select(
            'team_id',
            'point_in_category',
            'match_count',
            'rank1',
            'rank2',
            'rank3',
            'rank4',
        )
        ->when($request->is_combine_carried_over_point, function ($query) {
            $query->selectRaw('carried_over_point');
        }, function ($query) {
            $query->selectRaw('0 as carried_over_point');
        })
        ->when($request->is_combine_carried_over_point, function ($query) {
            $query->selectRaw('carried_over_point + point_in_category as sum_point');
        }, function ($query) {
            $query->selectRaw('0 + point_in_category as sum_point');
        })
        ->when($request->is_combine_carried_over_point, function ($query) {
            $query->selectRaw('rank() OVER (ORDER BY carried_over_point + point_in_category DESC) AS team_rank');
        }, function ($query) {
            $query->selectRaw('rank() OVER (ORDER BY 0 + point_in_category DESC) AS team_rank');
        })
        ->joinSub($teamPointInCategory, 'tr', function (JoinClause $join) {
            $join->on('team_id', '=', 'tr.team_id_tr');
        })
        ->equalSeasonId($request->season_id)
        ->equalMatchCategoryId($request->match_category_id)
        ->orderBy('sum_point', 'desc')
        ->get();

        $request->merge([
            'teamRankings' => $teamRankings,
            'matchLastDateDisplay' => $this->getMatchLastDateDisplay(
                $request->season_id,
                $request->match_category_id
            ),
        ]);

        return $request;
    }
}
