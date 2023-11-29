<?php

use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\ClientPlanController;
use App\Http\Controllers\Api\V1\ClientUserController;
use App\Http\Controllers\Api\V1\ConsumptionPlanController;
use App\Http\Controllers\Api\V1\DataController;
use App\Http\Controllers\Api\V1\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'cors'], function () {

    Route::post('register', [HomeController::class, 'signUp']);
    Route::post('login', [HomeController::class, 'signIn']);
    Route::post('forgot-password', [HomeController::class, 'forgotPassword']);
    Route::post('reset-password', [HomeController::class, 'resetPassword']);
    Route::get('verify-email', [HomeController::class, 'verify'])->name('verification.verify');


    Route::group(['middleware' => 'auth:sanctum', 'verified'], function () {
        Route::post('logout', [HomeController::class, 'logOut']);
        Route::get('get-user', [HomeController::class, 'getUser']);
        Route::post('change-password', [HomeController::class, 'changePassword']);

        // Get Data List
        Route::get('get-client', [DataController::class, 'client']);
        Route::get('get-client-plan', [DataController::class, 'clientPlan']);
        Route::get('get-client-user', [DataController::class, 'clientUser']);

        Route::apiResources([
            'client' => ClientController::class,
            'client-user' => ClientUserController::class,
            'consumption-plan' => ConsumptionPlanController::class,
        ]);
        Route::get('client-user-index/{id}', [ClientUserController::class, 'client_user_index']);
        Route::get('consumption-plan-index/{id}', [ConsumptionPlanController::class, 'client_user_index']);
        
        Route::get('client-plans/{id}', [ClientPlanController::class, 'get_client_plans']);
    });
});
