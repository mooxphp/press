<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/', function () {
        return Redirect::to('https://'.$_SERVER['SERVER_NAME'].'/wp');
    });

    Route::get('/admin/logout', function () {
        Auth::logout();
        request()->session()->invalidate();

        return Redirect::to('https://'.$_SERVER['SERVER_NAME'].'/');
    });

    Route::get('/register', function () {
        if (Auth::check()) {
            return Redirect::to('https://'.$_SERVER['SERVER_NAME'].'/');
        }

        return view('filament-panels::pages.auth.register');
    });

    // Catchall route must be a the bottom of the file
    Route::any('{any}', function ($any) {
        if (! str_contains(request()->server()['REQUEST_URI'], '/wp/')) {
            return redirect('/wp/'.ltrim(request()->path(), '/'));
        }
    })->where('any', '.*');
});
