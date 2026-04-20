<?php

use App\Http\Controllers\Admin\AdminUserController as AdminUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FoodController as AdminFoodController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */

Route::get('/homepage', [FoodController::class, 'showLatestFoods'])->name('homepage')->middleware('auth');

Route::get('/menu', [FoodController::class, 'index'])->name('menu')->middleware('auth');

Route::get('/foods', [FoodController::class, 'index'])->name('foods.index')->middleware('auth');

Route::get('/foods/{id}', [FoodController::class, 'show'])->name('foods.show')->middleware('auth');

Route::get('/promotions', [FoodController::class, 'promotions'])->name('promotions')->middleware('auth');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

    // Admin Routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // User Management
        Route::resource('users', AdminUserController::class, ['as' => 'admin']);

        // Menu Management (Foods)
        Route::resource('foods', AdminFoodController::class, ['as' => 'admin']);

        // Promotions Management
        Route::resource('promotions', AdminPromotionController::class, ['as' => 'admin']);

        // Orders Management
        Route::resource('orders', App\Http\Controllers\Admin\OrderController::class, ['as' => 'admin']);
    });
});

Route::get('/about', function () {
    return view('about');
})->name('about');
