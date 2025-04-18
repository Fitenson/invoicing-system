<?php

use Illuminate\Support\Facades\Route;

/**
 *  View Controllers
 */
use App\Modules\Auth\Controller\AuthViewController;
use App\Modules\User\Controller\UserViewController;

/**
 *  Controllers
*/
use App\Modules\Auth\Controller\AuthController;
use App\Modules\Dashboard\Controller\DashboardController;
use App\Modules\User\Controller\UserController;

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
// Guest routes
// Guest routes (web + guest)
Route::middleware(['web', 'guest'])->group(function () {
    Route::get('/login', [AuthViewController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthViewController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// Protected routes (web + auth)
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user', [UserViewController::class, 'index'])->name('users.index');

    Route::get('/user/create', [UserViewController::class, 'create'])->name('users.create');

    Route::get('/user/{id}', [UserViewController::class, 'show'])->name('users.show');


    Route::get('/user/update/{id}', [UserViewController::class, 'update'])->name('users.update');
});
