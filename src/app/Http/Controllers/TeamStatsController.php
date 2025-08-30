<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * チームスタッツコントローラー
 */
class TeamStatsController extends Controller
{
    /**
     *
     */
    public function index(Request $request): View
    {
        return View('team-stats.index',
            compact('request'),
        );
    }
}
