<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FoodController as AdminFoodController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
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

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================

Auth::routes();

// ============================================================================
// AUTHENTICATED USER ROUTES
// ============================================================================

Route::middleware('auth')->group(function () {
    // Dashboard/Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/homepage', [FoodController::class, 'showLatestFoods'])->name('homepage');

    // Food Browsing
    Route::get('/menu', [FoodController::class, 'index'])->name('menu');
    Route::get('/foods', [FoodController::class, 'index'])->name('foods.index');
    Route::get('/foods/{id}', [FoodController::class, 'show'])->name('foods.show');
    Route::post('/foods/{id}/toggle-availability', [FoodController::class, 'toggleAvailability'])->name('foods.toggleAvailability');

    // Promotions
    Route::get('/promotions', [FoodController::class, 'promotions'])->name('promotions');

    // Shopping Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('add/{id}', [CartController::class, 'add'])->name('add');
        Route::post('remove/{id}', [CartController::class, 'remove'])->name('remove');
        Route::post('update/{id}', [CartController::class, 'update'])->name('update');
        Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
    });

    // Payment
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');

    // User Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('{id}', [OrderController::class, 'show'])->name('show');
    });

    // ========================================================================
    // ADMIN ROUTES
    // ========================================================================

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::resource('users', AdminUserController::class);

        // Food/Menu Management
        Route::resource('foods', AdminFoodController::class);

        // Promotions Management
        Route::resource('promotions', AdminPromotionController::class);

        // Orders Management
        Route::resource('orders', AdminOrderController::class);
    });
});
