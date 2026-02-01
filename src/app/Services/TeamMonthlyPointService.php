<?php

namespace App\Services;

use App\Enums\BlankInList;
use App\Models\MatchCategory;
use App\Models\MatchResult;
use App\Models\Season;
use App\Models\Team;
use App\Traits\CommonFunctionsTrait;
use App\Traits\MstatsFunctionsTrait;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TeamMonthlyPointService
{
    use CommonFunctionsTrait;
    use MstatsFunctionsTrait;

    /**
     * チーム月毎ポイント表示のためのデータを準備します。
     *
     * - クエリパラメータのデフォルト値を確保
     * - マスタデータ（seasons, matchCategories）を追加
     * - チーム毎、月毎のポイント集計データを作成
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

        // チーム月毎ポイントデータを準備
        $monthlyPointData = $this->prepareMonthlyPointData($request);

        $request->merge([
            'monthlyPointData' => $monthlyPointData['data'],
            'months' => $monthlyPointData['months'],
            'teams' => $monthlyPointData['teams'],
            'matchLastDateDisplay' => $this->getMatchLastDateDisplay(
                $request->season_id,
                $request->match_category_id
            ),
        ]);

        return $request;
    }

    /**
     * チーム月毎のポイントデータを準備する
     *
     * @param Request $request
     * @return array
     */
    private function prepareMonthlyPointData(Request $request): array
    {
        // 選手所属テーブル定義を取得
        $playerAffiliation = $this->getDefinitionOfPlayerAffiliation($request->season_id);

        // 月毎のポイントを集計するクエリを実行
        $monthlyPoints = MatchResult::select('team_id')
            ->selectRaw('YEAR(match_information.match_date) as year')
            ->selectRaw('MONTH(match_information.match_date) as month')
            ->selectRaw('SUM(point + IFNULL(penalty, 0)) as point')
            ->selectRaw('COUNT(DISTINCT match_information.match_date) as match_count')
            ->join('match_information', 'match_results.match_id', '=', 'match_information.match_id')
            ->joinSub($playerAffiliation, 'pa', function (JoinClause $join) {
                $join->on('player_id', '=', 'pa.player_id_pa');
            })
            ->whereHas('matchInformation.matchSchedule', function ($query) use ($request) {
                $query->equalSeasonId($request->season_id);
                $query->equalMatchCategoryId($request->match_category_id);
            })
            ->groupBy('team_id', 'year', 'month')
            ->orderBy('team_id')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // 全チームを取得（既に所属している可能性があるものは限定）
        $allTeams = Team::whereHas('playerAffiliations', function ($query) use ($request) {
            $query->where('season_id', $request->season_id);
        })
        ->orderBy('team_name')
        ->get();

        // 月のリストを抽出
        $months = $monthlyPoints
            ->map(fn($item) => sprintf('%04d/%02d', $item->year, $item->month))
            ->unique()
            ->sort()
            ->values();

        // チーム毎のデータを整形
        $data = [];
        foreach ($allTeams as $team) {
            $teamData = [
                'team_id' => $team->team_id,
                'team_name' => $team->team_name,
                'background_color' => $team->background_color,
                'monthly_points' => [],
            ];

            // 全ての月に対してポイントを取得（なければ0）
            foreach ($months as $month) {
                $monthParts = explode('/', $month);
                $year = (int)$monthParts[0];
                $monthNum = (int)$monthParts[1];

                $monthlyPoint = $monthlyPoints->firstWhere(fn($item) =>
                    $item->team_id === $team->team_id &&
                    $item->year === $year &&
                    $item->month === $monthNum
                );

                $teamData['monthly_points'][$month] = [
                    'point' => $monthlyPoint->point ?? 0,
                    'match_count' => $monthlyPoint->match_count ?? 0,
                ];
            }

            // 合計ポイントを計算
            $teamData['total_point'] = collect($teamData['monthly_points'])
                ->sum('point');

            $data[] = $teamData;
        }

        // 合計ポイントで降順ソート
        usort($data, fn($a, $b) => $b['total_point'] <=> $a['total_point']);

        return [
            'data' => $data,
            'months' => $months,
            'teams' => $allTeams,
        ];
    }

    /**
     * 指定チーム・月の選手毎のポイントを取得する
     *
     * @param int $teamId
     * @param int $year
     * @param int $month
     * @param int $seasonId
     * @param int $matchCategoryId
     * @return Collection
     */
    public function getPlayerPointsForMonth(int $teamId, int $year, int $month, int $seasonId, int $matchCategoryId): Collection
    {
        // 選手所属テーブル定義を取得
        $playerAffiliation = $this->getDefinitionOfPlayerAffiliation($seasonId);

        // 選手毎のポイントを集計
        $playerPoints = MatchResult::select('match_results.player_id')
            ->selectRaw('SUM(point) as point')
            ->selectRaw('SUM(IFNULL(penalty, 0)) as penalty')
            ->selectRaw('SUM(point + IFNULL(penalty, 0)) as net_point')
            ->selectRaw('CONCAT(players.player_last_name, " ", players.player_first_name) as player_name')
            ->selectRaw('SUM(CASE WHEN rank = 1 THEN 1 ELSE 0 END) as rank1')
            ->selectRaw('SUM(CASE WHEN rank = 2 THEN 1 ELSE 0 END) as rank2')
            ->selectRaw('SUM(CASE WHEN rank = 3 THEN 1 ELSE 0 END) as rank3')
            ->selectRaw('SUM(CASE WHEN rank = 4 THEN 1 ELSE 0 END) as rank4')
            ->join('match_information', 'match_results.match_id', '=', 'match_information.match_id')
            ->joinSub($playerAffiliation, 'pa', function (JoinClause $join) {
                $join->on('match_results.player_id', '=', 'pa.player_id_pa');
            })
            ->join('players', 'match_results.player_id', '=', 'players.player_id')
            ->whereHas('matchInformation.matchSchedule', function ($query) use ($seasonId, $matchCategoryId) {
                $query->when($seasonId != BlankInList::EXIST->value, function ($q) use ($seasonId) {
                    $q->equalSeasonId($seasonId);
                });
                $query->when($matchCategoryId != BlankInList::EXIST->value, function ($q) use ($matchCategoryId) {
                    $q->equalMatchCategoryId($matchCategoryId);
                });
            })
            ->where('pa.team_id', $teamId)
            ->whereYear('match_information.match_date', $year)
            ->whereMonth('match_information.match_date', $month)
            ->groupBy('match_results.player_id', 'players.player_last_name', 'players.player_first_name')
            ->orderByDesc('net_point')
            ->get()
            ->map(function ($item) {
                return [
                    'player_id' => $item->player_id,
                    'player_name' => $item->player_name,
                    'point' => round($item->point, 1),
                    'penalty' => round($item->penalty, 1),
                    'net_point' => round($item->net_point, 1),
                    'rank_detail' => sprintf('%d-%d-%d-%d', $item->rank1, $item->rank2, $item->rank3, $item->rank4),
                ];
            });

        return $playerPoints;
    }
}
