<?php

use App\Http\Controllers\MatchResultController;
use App\Http\Controllers\MatchScheduleController;
use App\Http\Controllers\PlayerAffiliationController;
use App\Http\Controllers\PlayerStatsController;
use App\Http\Controllers\SeasonPlayerRankingController;
use App\Http\Controllers\AllPlayerRankingController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamPointChartController;
use App\Http\Controllers\TeamRankingController;
use App\Http\Controllers\TeamStatsController;
use App\Http\Controllers\TeamMonthlyPointController;
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
    ->name('match-schedules');

// 試合日程
route::get('/match-results', [MatchResultController::class, 'index'])
    ->name('match-results');

// チームランキング
route::get('/team-ranking', [TeamRankingController::class, 'index'])
    ->name('team-ranking');

// チーム月毎ポイント
route::get('/team-monthly-point', [TeamMonthlyPointController::class, 'index'])
    ->name('team-monthly-point');

// チームスタッツ
route::get('/team-stats', [TeamStatsController::class, 'index'])
    ->name('team-stats');

// チームポイントチャート
route::get('/team-point-chart', [TeamPointChartController::class, 'index'])
    ->name('team-point-chart');

// シーズン選手ランキング
route::get('/season-player-ranking', [SeasonPlayerRankingController::class, 'index'])
    ->name('season-player-ranking');

// 選手成績
route::get('/player-stats', [PlayerStatsController::class, 'index'])
    ->name('player-stats');

// オール選手ランキング
route::get('/all-player-ranking', [AllPlayerRankingController::class, 'index'])
    ->name('all-player-ranking');

Route::get('/bootstrap', function () {
    return view('bootstrap');
});
