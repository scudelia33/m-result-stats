<?php

namespace App\Http\Controllers;

use App\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index (TeamService $service)
    {
        $teams = $service->getAllOrderedTeams();
        return View('team.index', compact('teams'));
    }
}
