<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {

    // redirect_index + wordpress_slug

    Route::get('/', function () {
        return Redirect::to('https://'.$_SERVER['SERVER_NAME'].'/wp');
    });

    // redirect_logout

    Route::get('/moox/logout', function () {
        Auth::logout();
        request()->session()->invalidate();

        return Redirect::to('https://'.$_SERVER['SERVER_NAME'].'/');
    });

    // enable_registration

    Route::get('/register', function () {
        if (Auth::check()) {
            return Redirect::to('https://'.$_SERVER['SERVER_NAME'].'/');
        }

        return view('filament-panels::pages.auth.register');
    });

    // redirect_to_wp + wordpress_slug

    // Catchall route must be a the bottom of the file
    Route::any('{any}', function ($any) {
        if (! str_contains(request()->server()['REQUEST_URI'], '/wp/')) {
            return redirect('/wp/'.ltrim(request()->path(), '/'));
        }
    })->where('any', '.*');
});
