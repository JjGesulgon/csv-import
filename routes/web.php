<?php

// Login
Route::get('/login', 'Auth\LoginController@showLoginForm');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout');

// Forget Password
Route::get('/password/reset/', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email/', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

// Reset Password
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('show-password-reset-form');
Route::post('/password/reset/', 'Auth\ResetPasswordController@reset')->name('password.reset');

// Register
Route::get('register/', 'Auth\RegisterController@showRegistrationForm')->name('show-registration-form');
Route::post('register/', 'Auth\RegisterController@register')->name('register');

// Spa
Route::get('/{any}', 'SpaController@index')->where('any', '.*')->name('index');
