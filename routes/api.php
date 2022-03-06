<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthAPIController;
use App\Http\Controllers\API\UserAPIController;

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
    'prefix' => 'v1',
], function () {
    Route::post('/login', [AuthAPIController::class, 'login']);
    Route::post('/register', [AuthAPIController::class, 'register']);
    Route::get('/email/verify/{id}', [AuthAPIController::class, 'verify'])->name('verification.verify');
    Route::get('/email/resend', [AuthAPIController::class, 'resend'])->name('verification.resend');
    Route::middleware('auth:api')->group(function () {
        Route::resource('users', 'UserAPIController');
        Route::get('/users/restore/{id}', [UserAPIController::class, 'restore']);
        Route::put('/users/disable/{id}', [UserAPIController::class, 'disable']);
        Route::get('/current_user', [UserAPIController::class, 'currentUser']);
        Route::resource('curriculum_vitaes', 'CurriculumVitaeAPIController');
        Route::resource('roles', 'RoleAPIController');
        Route::resource('permissions', 'PermissionAPIController');
        Route::resource('issues', 'IssueAPIController');
        Route::resource('merchants', 'MerchantAPIController');
        Route::post('/logout', [AuthAPIController::class, 'logout']);
    });
});
