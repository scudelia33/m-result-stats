<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * 選手所属コントローラ
 */
class PlayerAffiliationController extends Controller
{
    /**
     *
     */
    public function index(Request $request): View
    {
        return View('player-affiliation.index',
            compact('request'),
        );
    }
}
