<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:manage-users')->group(function(){
    Route::resource('/users', 'UsersController', ['except' => ['show', 'store']]);

});

Route::view('/invoices', 'invoices')->middleware('auth');;

Route::view('/invoice/{id}', 'invoice')->middleware('auth');;

Route::view('/obrclist', 'obrclist')->middleware('auth');;

