<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);
    Route::post('me', [\App\Http\Controllers\AuthController::class,'me']);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function () {
   Route::get('tickets', [\App\Http\Controllers\UserController::class, 'getTickets']);
   Route::delete('tickets/returnTicket/{id}', [\App\Http\Controllers\UserController::class, 'returnTicket']);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'movie'
], function () {
    Route::get('/', [\App\Http\Controllers\MovieController::class, 'all']);
    Route::post('/', [\App\Http\Controllers\MovieController::class, 'store']);
    Route::get('/getActual', [\App\Http\Controllers\MovieController::class, 'getActual']);
    Route::get('/{id}', [\App\Http\Controllers\MovieController::class, 'findById'])->where('id', '[0-9]+');
    Route::put('/{id}', [\App\Http\Controllers\MovieController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\MovieController::class, 'delete']);
    Route::get('/search', [\App\Http\Controllers\MovieController::class, 'findByKeyword']);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'hall'
], function () {
    Route::get('/', [\App\Http\Controllers\HallController::class, 'all']);
    Route::post('/', [\App\Http\Controllers\HallController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\HallController::class, 'get'])
        ->where('id', '[0-9]+');
    Route::get('/{hallId}/session/{sessionId}', [\App\Http\Controllers\HallController::class, 'getSeats'])
        ->where(['hallId'=> '[0-9]+', 'sessionId' =>'[0-9]+']);
    Route::put('/{id}', [\App\Http\Controllers\HallController::class, 'update'])
        ->where('id', '[0-9]+');
    Route::delete('/{id}', [\App\Http\Controllers\HallController::class, 'delete'])
        ->where('id', '[0-9]+');
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'session'
], function () {
    Route::get('/', [\App\Http\Controllers\SessionController::class, 'all']);
    Route::post('/', [\App\Http\Controllers\SessionController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\SessionController::class, 'get'])->where('id', '[0-9]+');
    Route::put('/{id}', [\App\Http\Controllers\SessionController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/{id}', [\App\Http\Controllers\SessionController::class, 'delete'])->where('id', '[0-9]+');
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'ticket'
], function () {
    Route::get('/', [\App\Http\Controllers\TicketController::class, 'all']);
    Route::post('/', [\App\Http\Controllers\TicketController::class, 'byTicket']);
    Route::get('/{id}', [\App\Http\Controllers\TicketController::class, 'get'])->where('id', '[0-9]+');
    Route::put('/{id}', [\App\Http\Controllers\TicketController::class, 'update'])->where('id', '[0-9]+');
});
