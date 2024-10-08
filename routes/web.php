<?php

use App\Http\Controllers\PlayerAffiliationController;
use App\Http\Controllers\TeamController;
use App\Http\Middleware\PlayerAffiliationIndexMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// チーム
route::get('/teams', [TeamController::class, 'index']);

// 選手所属
route::get('/player-affiliations', [PlayerAffiliationController::class, 'index'])
    ->middleware([
        PlayerAffiliationIndexMiddleware::class
    ])->
    name('player-affiliations');

Route::get('/bootstrap', function () {
    return view('bootstrap');
});
