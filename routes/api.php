<?php

use App\Http\Controllers\api\v1\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\api\v1'], function ($api) {
    $api->post('register', 'HomeController@signUp');
    $api->post('login', 'HomeController@signIn');
    $api->post('forgot-password', 'HomeController@forgotPassword');
    $api->post('reset-password', 'HomeController@resetPassword');
    $api->get('verify-email', 'HomeController@verify')->name('verification.verify');
    $api->get('send-msg', 'HomeController@test');

    Route::group(['middleware' => ['auth:sanctum', 'verified']], function ($auth) {
        $auth->post('logout', 'HomeController@logOut');
        $auth->get('get-user', 'HomeController@getUser');
        $auth->post('change-password', 'HomeController@changePassword');
    });
});
