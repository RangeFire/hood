<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Middleware\JWTAuthentication;

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

Route::prefix('auth')->group(function () {
    Route::post('login', [Controllers\AuthController::class, 'apiLogin']);
    Route::post('register', [Controllers\AuthController::class, 'apiRegister']);
    Route::post('sendPasswordResetLink', [Controllers\AuthController::class, 'apiSendPasswordResetMail']);
    Route::post('resetPassword/{resetToken}', [Controllers\AuthController::class, 'apiResetPassword']);
});

Route::middleware([JWTAuthentication::class])->group(function () {
    
    Route::get('test', function () {
        return response()->json(['error' => null, 'data' => 'test'], 200);
    });

});
