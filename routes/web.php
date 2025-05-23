<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\SocialAuthController;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\CartController;

Route::get('/', function () {
    return view('welcome');
});



//  User Authentication
Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// Profile Management
Route::get('verify', [UsersController::class, 'verify'])->name('verify');
Route::get('/forgot-password', [UsersController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [UsersController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [UsersController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [UsersController::class, 'resetPassword'])->name('password.update');

 // User Listing and Search
Route::get('/users/list', [UsersController::class, 'list'])->name('users.list');

// User CRUD Operations
Route::get('/users/create', [UsersController::class, 'createRoll'])->name('users_create');
Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users_edit');
Route::post('/users/{user}/save', [UsersController::class, 'save'])->name('users_save');
Route::delete('/users/{user}/delete', [UsersController::class, 'delete'])->name('users_delete');
// Password Management
Route::get('/users/{user}/edit-password', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('/users/{user}/save-password', [UsersController::class, 'savePassword'])->name('save_password');

// User Profile
Route::get('/users/{user}/profile', [UsersController::class, 'profile'])->name('profile');



//  Products
Route::get('/category', [ProductsController::class, 'category'])->name('products.category');
Route::get('/category/{category}', [ProductsController::class, 'ListByCategory'])->name('products.byCategory');
Route::get('/product/{id}', [ProductsController::class, 'productDetails'])->name('products.details');

//  Manage Products
Route::get('/manage', [ProductsController::class, 'manage'])->name('products.manage');
Route::get('/manage/edit/{product?}', [ProductsController::class, 'edit'])->name('products.edit');
Route::post('/manage/save/{product?}', [ProductsController::class, 'save'])->name('products.save');
Route::delete('/manage/delete/{product}', [ProductsController::class, 'delete'])->name('products.delete');

//  Social Authentication
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
Route::get('auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('facebook.login');
Route::get('auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback']);
Route::get('/auth/github/redirect', [SocialAuthController::class, 'redirectToGithub'])->name('github.redirect');
Route::get('/auth/github/callback', [SocialAuthController::class, 'handleGithubCallback']);

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{cart}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');



