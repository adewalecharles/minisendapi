<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
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

Route::group(['prefix' => 'v1'], function() {

    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });

    Route::group(['middleware' => 'auth:sanctum'], function() {

        Route::get('/user',[AuthController::class,'user']);
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/email', [MailController::class, 'store'])->middleware('ability:send-mail');
        Route::get('/get-emails', [MailController::class, 'index']);
        Route::get('/get-email/{uuid}', [MailController::class, 'show']);

        Route::get('/email-token', [MailController::class, 'getMailToken']);

    });
});
