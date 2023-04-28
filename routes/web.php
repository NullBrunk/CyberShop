<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('static.index');
});

Route::get('/login', function () {
    return view('login.login');
});

Route::get('/signup', function () {
    return view('login.signup');
});

Route::get('/about', function () {
    return view('static.about');
});

