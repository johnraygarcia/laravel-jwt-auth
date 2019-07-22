<?php

use Illuminate\Http\Request;

Route::group([

    'middleware' => 'api',
    'namespace' => 'Auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('reset-password', 'ResetPasswordController@sendPasswordResetEmail');
    Route::post('change-password', 'ResetPasswordController@changePassword');

});
