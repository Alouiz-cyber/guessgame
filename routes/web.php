<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', [GameController::class, 'index']);
Route::post('/guess', [GameController::class, 'makeGuess']);

