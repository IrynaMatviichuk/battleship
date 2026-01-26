<?php

use App\Http\Controllers\BoardController;
use Illuminate\Support\Facades\Route;

Route::get('/boards/{id}', [BoardController::class, 'show']);
