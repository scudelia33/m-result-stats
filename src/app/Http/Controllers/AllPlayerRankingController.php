<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Services\AllPlayerRankingService;

/**
 * オール選手ランキングコントローラ
 */
class AllPlayerRankingController extends Controller
{
    private AllPlayerRankingService $service;

    public function __construct(AllPlayerRankingService $service)
    {
        $this->service = $service;
    }
    /**
     *
     */
    public function index(Request $request): View
    {
        $request = $this->service->prepareIndexData($request);

        return View('all-player-ranking.index', compact('request'));
    }
}
