<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix("auth")->group(function () {
    Route::post("login", LoginController::class)->name("login"); // esse name á para nomear a rota para utilizar em redirects ou urls "{{ route('login') }}"
    Route::post("register",RegisterController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post("logout",LogoutController::class);
    });
});
