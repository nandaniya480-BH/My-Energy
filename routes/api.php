<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\api\v1'], function ($api) {
    $api->post('register', 'HomeController@signUp');
    $api->post('login', 'HomeController@signIn');
    $api->post('forgot-password', 'HomeController@forgotPassword');
    $api->post('reset-password', 'HomeController@resetPassword');
    $api->get('verify-email', 'HomeController@verify')->name('verification.verify');

    Route::group(['middleware' => ['auth:sanctum', 'verified']], function ($auth) {
        $auth->post('logout', 'HomeController@logOut');
        $auth->get('get-user', 'HomeController@getUser');
        $auth->post('change-password', 'HomeController@changePassword');
    });
});
