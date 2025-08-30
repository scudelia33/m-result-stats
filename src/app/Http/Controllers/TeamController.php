<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index ()
    {
        $teams = Team::sortable()->get();
        return View('team.index',
            compact('teams')
        );
    }
}
