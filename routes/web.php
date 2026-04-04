<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WelcomePageController;

Route::get('/', function () {
    if(Auth::check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::prefix('/admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])
        ->name('admin.users.index');

    Route::patch('/users/{user}/toggle-block', [AdminUserController::class, 'toggleBlock'])
        ->name('admin.users.toggle-block');

    Route::patch('/users/{user}/role', [AdminUserController::class, 'changeRole'])
        ->name('admin.users.change-role');


    Route::get('/categories', [AdminCategoryController::class, 'index'])
        ->name('admin.categories.index');

    Route::post('/categories', [AdminCategoryController::class, 'store'])
        ->name('admin.categories.store');

    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])
        ->name('admin.categories.update');

    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])
        ->name('admin.categories.destroy');


    Route::get('/logs', [AdminLogController::class, 'index'])
        ->name('admin.logs.index');

});

Route::middleware('auth')->group(function () {
    //nav
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportsController::class, 'reports'])->name('reports');
    Route::get('/my-account', [AccountController::class, 'account'])->name('my-account');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //account updates
    Route::post('/account/update-name', [AccountController::class, 'updateName'])->name('my-account.updateName');
    Route::post('/account/update-email', [AccountController::class, 'updateEmail'])->name('my-account.updateEmail');
    Route::post('/account/update-password', [AccountController::class, 'updatePassword'])->name('my-account.updatePassword');
    Route::post('/account/update-preferences', [AccountController::class, 'updatePreferences'])->name('my-account.updatePreferences');

    //categories
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    Route::get('/categories', [CategoryController::class, 'index']);

    //reports - transactions
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/reports/list', [ReportsController::class, 'list']);
    Route::get('/reports/summary', [ReportsController::class, 'summary']);

    //dashboard chart data
    Route::get('/dashboard/balance-chart', [DashboardController::class, 'balanceChart']);
    Route::get('/dashboard/category-chart', [DashboardController::class, 'categoryChart']);
});

Route::get('/contact', [ContactController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::get('/about-us', [AuthorController::class, 'index'])->name('about-us');

