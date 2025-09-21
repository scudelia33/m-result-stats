<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index ()
    {
        // Use explicit orderBy to avoid relying on the removed Sortable trait
        $teams = Team::orderBy('team_name', 'asc')->get();
        return View('team.index',
            compact('teams')
        );
    }
}
