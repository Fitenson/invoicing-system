<?php

use Illuminate\Support\Facades\Route;

/**
 *  Views Controller
*/
use App\Modules\Auth\Controller\AuthViewController;
use App\Modules\Dashboard\Controller\DashboardViewController;

/**
 *  Functions Controller
*/
use App\Modules\Auth\Controller\AuthController;

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

// Redirect root URL
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// View Routes (only for guests)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthViewController::class, 'login'])->name('login');
    Route::get('/register', [AuthViewController::class, 'register'])->name('register');
});

// Action Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Dashboard Route (only for authenticated users)
Route::get('/dashboard', [DashboardViewController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');
