<?php

use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// チーム
route::get('/teams', [TeamController::class, 'index']);

Route::get('/bootstrap', function () {
    return view('bootstrap');
});
