<?php

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

const VERSION = 'v2';
const ID = '/{id}';

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
        Route::get(ID, [MainController::class, 'getToDo']);
        Route::get('', [MainController::class, 'getAllToDo']);
        Route::post('', [MainController::class, 'newToDo']);
        Route::put('', [MainController::class, 'updateToDo']);
        Route::delete(ID, [MainController::class, 'deleteToDo']);
        Route::delete('', [MainController::class, 'deleteAllToDo']);
    });
});
