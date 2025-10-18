<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\PlayerStatsService;

/**
 * 選手成績コントローラ
 */
class PlayerStatsController extends Controller
{
    private PlayerStatsService $service;

    public function __construct(PlayerStatsService $service)
    {
        $this->service = $service;
    }

    /**
     * 選手成績一覧表示
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('player-stats.index', compact('request'));
    }
}
