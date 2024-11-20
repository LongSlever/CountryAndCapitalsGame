<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//start game

Route::get('/', [MainController::class, 'startGame'])->name('startGame');

Route::post('/prepareGame', [MainController::class, 'prepareGame'])->name('prepareGame');

Route::get('/game', [MainController::class, 'game'])->name('game');

Route::get('answer/{answer}', [MainController::class, 'answer'])->name('answer');
