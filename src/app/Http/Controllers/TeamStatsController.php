<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\TeamStatsService;

/**
 * チームスタッツコントローラー
 */
class TeamStatsController extends Controller
{
    private TeamStatsService $service;

    public function __construct(TeamStatsService $service)
    {
        $this->service = $service;
    }
    /**
     *
     */
    /**
     * チームスタッツ画面を表示します。
     * サービスでデータ準備を行い、ビューに渡します。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('team-stats.index', compact('request'));
    }
}
