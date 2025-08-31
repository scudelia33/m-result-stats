<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * オール選手ランキングコントローラ
 */
class AllPlayerRankingController extends Controller
{
    /**
     *
     */
    public function index(Request $request): View
    {
        return View('all-player-ranking.index',
            compact('request'),
        );
    }
}
