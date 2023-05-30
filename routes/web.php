<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Details;
use App\Http\Controllers\Users;
use App\Http\Controllers\Index;


/*
|---------------------------------------------
| Render statics views 
|

*/

Route::get('/about', function () {
    return view('static.about');
}) -> name("about");

Route::get('/contact', function () {
    return view('static.contact');
}) -> name("contact");


/*
|---------------------------------------------
| Others 
|

*/

Route::get('/', Index::class ) -> name("root");



// Login / Signup

Route::post('/login',  [ Users::class, "get" ] );
Route::post('/signup', [ Users::class, "store" ] );


Route::get('/signup', function () {
    return view('login.signup');
}) -> name("signup");

Route::get('/login', function () {
    return view('login.login');
}) -> name("login");



// Disconnect

Route::get('/disconnect', function () {

    session_start();
    session_destroy();
    return redirect('/');

}) -> name("disconnect");



// Details 

Route::get('/details/{product_id}', Details::class );

// Articles && SearchBar
Route::get("/articles", function (){
    return view("articles");
}) -> name("articles");