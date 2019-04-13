<?php
Route::group([
    'middleware' => 'api',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('uploadUserImages', 'AuthController@uploadUserImages');

    Route::post('sendPasswordResetLink', 'PasswordResetController@sendEmail');
    Route::post('resetPassword', 'ChangePasswordController@process');

    Route::get('currentUser', 'AuthController@currentUser');

    Route::get('getGenders', 'API\GenderController@getAllGenders');

});