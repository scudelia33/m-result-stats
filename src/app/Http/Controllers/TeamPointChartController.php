<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * チームポイントチャートコントローラ
 */
class TeamPointChartController extends Controller
{
    /**
     *
     */
    public function index(Request $request): View
    {
        return View('team-point-chart.index',
            compact('request'),
        );
    }
}
