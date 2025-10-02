<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\TeamPointChartService;

/**
 * チームポイントチャートコントローラ
 */
class TeamPointChartController extends Controller
{
    private TeamPointChartService $service;

    public function __construct(TeamPointChartService $service)
    {
        $this->service = $service;
    }
    /**
     *
     */
    /**
     * チームポイントチャート画面を表示します。
     * サービスでデータ準備を行い、ビューに渡します。
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('team-point-chart.index', compact('request'));
    }
}
