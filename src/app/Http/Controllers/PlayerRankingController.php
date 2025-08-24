<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * 選手ランキングコントローラ
 */
class PlayerRankingController extends Controller
{
    /**
     *
     */
    public function index(Request $request): View
    {
        return View('player-ranking.index',
            compact('request'),
        );
    }
}
