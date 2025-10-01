<?php

use App\Http\Controllers\MatchResultController;
use App\Http\Controllers\MatchScheduleController;
use App\Http\Controllers\PlayerAffiliationController;
use App\Http\Controllers\SeasonPlayerRankingController;
use App\Http\Controllers\AllPlayerRankingController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamPointChartController;
use App\Http\Controllers\TeamRankingController;
use App\Http\Controllers\TeamStatsController;
use App\Http\Middleware\MatchScheduleIndexMiddleware;
use App\Http\Middleware\MatchResultIndexMiddleware;
use App\Http\Middleware\SeasonPlayerRankingIndexMiddleware;
use App\Http\Middleware\AllPlayerRankingIndexMiddleware;
use App\Http\Middleware\TeamPointChartMiddleware;
use App\Http\Middleware\TeamRankingIndexMiddleware;
use App\Http\Middleware\TeamStatsIndexMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// チーム
route::get('/teams', [TeamController::class, 'index']);

// 選手所属
route::get('/player-affiliations', [PlayerAffiliationController::class, 'index'])
    ->name('player-affiliations');

// 試合日程
route::get('/match-schedules', [MatchScheduleController::class, 'index'])
    ->middleware([
        MatchScheduleIndexMiddleware::class
    ])->
    name('match-schedules');

// 試合日程
route::get('/match-results', [MatchResultController::class, 'index'])
    ->middleware([
        MatchResultIndexMiddleware::class
    ])->
    name('match-results');

// チームランキング
route::get('/team-ranking', [TeamRankingController::class, 'index'])
    ->middleware([
        TeamRankingIndexMiddleware::class
    ])->
    name('team-ranking');

// チームスタッツ
route::get('/team-stats', [TeamStatsController::class, 'index'])
    ->middleware([
        TeamStatsIndexMiddleware::class
    ])->
    name('team-stats');

// チームポイントチャート
route::get('/team-point-chart', [TeamPointChartController::class, 'index'])
    ->middleware([
        TeamPointChartMiddleware::class
    ])->
    name('team-point-chart');

// シーズン選手ランキング
route::get('/season-player-ranking', [SeasonPlayerRankingController::class, 'index'])
    ->middleware([
        SeasonPlayerRankingIndexMiddleware::class
    ])->
    name('season-player-ranking');

// オール選手ランキング
route::get('/all-player-ranking', [AllPlayerRankingController::class, 'index'])
    ->middleware([
        AllPlayerRankingIndexMiddleware::class
    ])->
    name('all-player-ranking');

Route::get('/bootstrap', function () {
    return view('bootstrap');
});
