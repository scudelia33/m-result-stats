<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\TeamRankingService;

/**
 * チームランキングコントローラ
 */
class TeamRankingController extends Controller
{
    private TeamRankingService $service;

    public function __construct(TeamRankingService $service)
    {
        $this->service = $service;
    }
    /**
     * チームランキング一覧画面を表示します。
     * サービスでデータ準備を行い、ビューに渡します。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('team-ranking.index', compact('request'));
    }
}
