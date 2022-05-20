<?php

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

const VERSION = 'v3';

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix(VERSION)->group(function () {
    Route::prefix('/todos')->group(function () {
        Route::get('/{userId}/{id}', [MainController::class, 'getToDo']);
        Route::get('/{userId}', [MainController::class, 'getAllToDo']);
        Route::post('/{userId}', [MainController::class, 'newToDo']);
        Route::put('/{userId}/{id}', [MainController::class, 'updateToDo']);
        Route::delete('/{userId}/{id}', [MainController::class, 'deleteToDo']);
        Route::delete('/{userId}', [MainController::class, 'deleteAllToDo']);
    });

    // Route::prefix('/users')->group(function () {
    //     Route::post('/register', [MainController::class, 'newToDo']);
    //     Route::post('/login', [MainController::class, 'newToDo']);
    //     Route::post('/logout', [MainController::class, 'newToDo']);
    // });
});
