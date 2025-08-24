<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * チームランキングコントローラ
 */
class TeamRankingController extends Controller
{
    /**
     *
     */
    public function index(Request $request): View
    {
        return View('team-ranking.index',
            compact('request'),
        );
    }
}
