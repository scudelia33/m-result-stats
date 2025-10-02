<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\SeasonPlayerRankingService;

/**
 * シーズン選手ランキングコントローラ
 */
class SeasonPlayerRankingController extends Controller
{
    private SeasonPlayerRankingService $service;

    public function __construct(SeasonPlayerRankingService $service)
    {
        $this->service = $service;
    }
    /**
     * シーズン選手ランキング画面を表示します。
     * サービスでデータ準備を行い、ビューに渡します。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('season-player-ranking.index', compact('request'));
    }
}
