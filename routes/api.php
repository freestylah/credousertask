<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', 'Auth\RegisterController@register');
Route::post('login', 'Auth\AuthController@login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function () {
  Route::post('logout', 'Auth\AuthController@logout')->name('logout');
  Route::get('users/show/{id}', 'Auth\AuthController@showuser')->name('showuser');
  Route::delete('users/del/{id}', 'Auth\AuthController@deluser')->name('deluser');
  Route::get('users/showall', 'Auth\AuthController@showusers')->name('showusers');
  Route::put('users/edit/{id}', 'Auth\AuthController@updateuser')->name('updateuser');
});
