<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\SocialAuthController;
use App\Http\Controllers\Web\ProductsController;

Route::get('/', function () {
    return view('welcome');
});



// ✅ User Authentication & Profile Management
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');


Route::get('verify', [UsersController::class, 'verify'])->name('verify');
Route::get('/forgot-password', [UsersController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [UsersController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [UsersController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [UsersController::class, 'resetPassword'])->name('password.update');


Route::get('/category', [ProductsController::class, 'category'])->name('products.category');
Route::get('/category/{category}', [ProductsController::class, 'ListByCategory'])->name('products.byCategory');


Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
Route::get('auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);
Route::get('/auth/github/redirect', [SocialAuthController::class, 'redirectToGithub'])->name('github.redirect');
Route::get('/auth/github/callback', [SocialAuthController::class, 'handleGithubCallback']);



