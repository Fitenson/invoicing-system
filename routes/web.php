<?php

use Illuminate\Support\Facades\Route;

/**
 *  View Controllers
 */
use App\Modules\Auth\Controller\AuthViewController;
use App\Modules\User\Controller\UserViewController;
use App\Modules\Project\Controller\ProjectViewController;

/**
 *  Controllers
*/
use App\Modules\Auth\Controller\AuthController;
use App\Modules\Dashboard\Controller\DashboardController;
use App\Modules\User\Controller\UserController;
use App\Modules\Project\Controller\ProjectController;

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

    //  User routes
    Route::prefix('user')->as('users.')->group(function () {
        Route::get('/', [UserViewController::class, 'index'])->name('index');

        //  Create routes
        Route::get('/create', [UserViewController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');

        Route::get('/{id}', [UserViewController::class, 'show'])->name('show');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');

        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });


    Route::prefix('project')->as('projects.')->group(function () {
        Route::get('/', [ProjectViewController::class, 'index'])->name('index');

        //  Create routes
        Route::get('/create', [ProjectViewController::class, 'create'])->name('create');
        Route::post('/store', [ProjectController::class, 'store'])->name('store');

        Route::get('/{id}', [ProjectViewController::class, 'show'])->name('show');
        Route::put('/update/{id}', [ProjectController::class, 'update'])->name('update');

        Route::delete('/{id}', [ProjectController::class, 'destroy'])->name('destroy');
    });
});
