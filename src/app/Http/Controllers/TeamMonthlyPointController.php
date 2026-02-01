<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TeamMonthlyPointService;

/**
 * チーム月毎ポイントコントローラ
 */
class TeamMonthlyPointController extends Controller
{
    private TeamMonthlyPointService $service;

    public function __construct(TeamMonthlyPointService $service)
    {
        $this->service = $service;
    }

    /**
     * チーム月毎ポイント一覧画面を表示します。
     * サービスでデータ準備を行い、ビューに渡します。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('team-monthly-point.index', compact('request'));
    }

    /**
     * 指定チーム・月の選手毎ポイントを取得するAPIエンドポイント
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPlayerPoints(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'team_id' => 'required|integer|min:1',
            'year' => 'required|integer|min:2000',
            'month' => 'required|integer|min:1|max:12',
            'season_id' => 'required|integer|min:0',
            'match_category_id' => 'required|integer|min:0',
        ]);

        $playerPoints = $this->service->getPlayerPointsForMonth(
            $validated['team_id'],
            $validated['year'],
            $validated['month'],
            $validated['season_id'],
            $validated['match_category_id']
        );

        // チーム名を取得
        $team = \App\Models\Team::find($validated['team_id']);
        $teamName = $team ? $team->team_name : '';

        // 合計ポイントを計算
        $totalPoint = $playerPoints->sum('net_point');

        return response()->json([
            'status' => 'success',
            'data' => $playerPoints,
            'month' => sprintf('%04d/%02d', $validated['year'], $validated['month']),
            'team_name' => $teamName,
            'total_point' => round($totalPoint, 1),
        ]);
    }
}
