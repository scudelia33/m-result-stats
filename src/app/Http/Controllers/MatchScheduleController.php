<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * 試合日程コントローラ
 */
class MatchScheduleController extends Controller
{
    /**
     *
     */
    public function index(Request $request): View
    {
        return View('match-schedule.index',
            compact('request'),
        );
    }
}
