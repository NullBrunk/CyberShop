<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Details;
use App\Http\Controllers\Signup;
use App\Http\Controllers\Login;
use App\Http\Controllers\Index;


/*
|---------------------------------------------
| Render statics views 
|

*/

Route::get('/about', function () {
    return view('static.about');
});

Route::get('/contact', function () {
    return view('static.contact');
});

Route::get('/detail', function () {
    return view('static.details');
});

/*
|---------------------------------------------
| Others 
|

*/

Route::get('/', Index::class );



// Login / Signup

Route::post('/login', Login::class );
Route::post('/signup', Signup::class );


Route::get('/signup', function () {
    return view('login.signup');
});

Route::get('/login', function () {
    return view('login.login');
});



// Disconnect

Route::get('/disconnect', function () {
    session_destroy();
    return redirect('/');
});



// Details 

Route::get('/details', Details::class );