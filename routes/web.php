<?php

use Illuminate\Support\Facades\Route;

/**
 *  View Controllers
 */
use App\Modules\Auth\Controller\AuthViewController;
use App\Modules\Dashboard\Controller\DashboardViewController;
use App\Modules\User\Controller\UserViewController;
use App\Modules\Project\Controller\ProjectViewController;
use App\Modules\Invoice\Controller\InvoiceViewController;

/**
 *  Controllers
 */
use App\Modules\Auth\Controller\AuthController;
use App\Modules\User\Controller\UserController;
use App\Modules\Project\Controller\ProjectController;
use App\Modules\Invoice\Controller\InvoiceController;

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
    Route::get('/', [DashboardViewController::class, 'index'])->name('dashboard');

    //  User routes
    Route::prefix('user')->as('users.')->group(function () {
        Route::get('/', [UserViewController::class, 'index'])->name('index');

        //  Render create User page
        Route::get('/create', [UserViewController::class, 'create'])->name('create');

        //  Create User API
        Route::post('/store', [UserController::class, 'store'])->name('store');

        //  Render User page
        Route::get('/{id}', [UserViewController::class, 'show'])->name('show');

        //  Update User API
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');

        //  Update User API
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });


    //  Project routes
    Route::prefix('project')->as('projects.')->group(function () {
        Route::get('/', [ProjectViewController::class, 'index'])->name('index');

        //  Render create Project page
        Route::get('/create', [ProjectViewController::class, 'create'])->name('create');

        //  Create Project API
        Route::post('/store', [ProjectController::class, 'store'])->name('store');

        //  Render Project page
        Route::get('/{id}', [ProjectViewController::class, 'show'])->name('show');

        //  Update Project API
        Route::put('/update/{id}', [ProjectController::class, 'update'])->name('update');

        //  Delete Project API
        Route::delete('/{id}', [ProjectController::class, 'destroy'])->name('destroy');
    });


    //  Invoice routes
    Route::prefix('invoice')->as('invoices.')->group(function () {
        //  Index route
        Route::get('/', [InvoiceViewController::class, 'index'])->name('index');

        //  Render create Invoice page
        Route::get('/create', [InvoiceViewController::class, 'create'])->name('create');
        //  Create Invoice API
        Route::post('/store', [InvoiceController::class, 'store'])->name('store');

        //  Render Invoice page
        Route::get('/{id}', [InvoiceViewController::class, 'show'])->name('show');

        //  Update Invoice API
        Route::put('/update/{id}', [InvoiceController::class, 'update'])->name('update');

        //  Delete Invoice API
        Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');

        //  Add project to an Invoice record API
        Route::post('/store-project/{id}', [InvoiceController::class, 'storeProject'])->name('storeProject');

        //  Remove project from an Invoice record API
        Route::delete('/destroy-project/{id}', [InvoiceController::class, 'destroyProjects'])->name('destroyProjects');

        //  Display PDF on browser
        Route::get('/generate-pdf/{id}', [InvoiceController::class, 'generatePDF'])->name('generatePDF');

        //  Send Email with Invoice PDF attached to the email
        Route::post('/send-email/{id}', [InvoiceController::class, 'sendEmail'])->name('sendEmail');
    });
});
