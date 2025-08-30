<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * 試合成績コントローラ
 */
class MatchResultController extends Controller
{
    /**
     *
     */
    public function index(Request $request): View
    {
        return View('match-result.index',
            compact('request'),
        );
    }
}
