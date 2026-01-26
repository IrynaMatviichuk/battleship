<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/boards/{id}', [BoardController::class, 'show']);
Route::get('/games/{id}', [GameController::class, 'show']);
