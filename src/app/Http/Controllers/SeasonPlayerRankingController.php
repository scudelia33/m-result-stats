<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * シーズン選手ランキングコントローラ
 */
class SeasonPlayerRankingController extends Controller
{
    /**
     *
     */
    public function index(Request $request): View
    {
        return View('season-player-ranking.index',
            compact('request'),
        );
    }
}
