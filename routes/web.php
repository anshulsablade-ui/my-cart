<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Mycart\AddressController;
use App\Http\Controllers\Mycart\Auth\AuthController as MycartAuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Mycart\CartController;
use App\Http\Controllers\Mycart\CheckoutController;
use App\Http\Controllers\Mycart\HomeController;
use App\Http\Controllers\Mycart\OrderController;
use App\Http\Controllers\Mycart\ProductController as MycartProductController;
use App\Http\Controllers\Mycart\WishlistController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('admin')->name('admin.')->group(function () {

    // Login & Logout
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::middleware('adminLogin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Category Routes
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Brand Routes
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/edit/{id}', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{id}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');

        // Product Routes
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    });
});



// Location Routes
Route::get('/getCountries', [LocationController::class, 'getCountries'])->name('getCountries');
Route::get('/getStates/{id}', [LocationController::class, 'getStates'])->name('getState');
Route::get('/getCities/{id}', [LocationController::class, 'getCities'])->name('getCity');



// Mycart Routes
Route::prefix('home')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/product/{slug}', [MycartProductController::class, 'detail'])->name('product.show');

    // Add To Cart Route
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'removeAll'])->name('cart.clear');

    
    Route::middleware('userLogin')->group(function () {

        // Wishlist Routes
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
        Route::delete('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

        // Checkout Routes
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

        Route::post('/checkout', [CheckoutController::class, 'processOrder'])->name('order.process');
        Route::post('/checkout/place-order', [CheckoutController::class, 'verifyPayment'])->name('order.verify-payment');
        Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'orderSuccess'])->name('order.success');


        // User Address Routes
        Route::get('/address', [AddressController::class, 'index'])->name('address');
        Route::post('/address', [AddressController::class, 'store'])->name('address.store');
        Route::get('/address/edit/{id}', [AddressController::class, 'edit'])->name('address.edit');
        Route::put('/address/{id}', [AddressController::class, 'update'])->name('address.update');
        Route::delete('/address/{id}', [AddressController::class, 'delete'])->name('address.delete');


        // order Routes
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');

        Route::get('/logout', [MycartAuthController::class, 'logout'])->name('logout');

        // Route::post('/stripe-payment', [CheckoutController::class, 'payment'])->name('stripe.payment');

    });
});

// Mycart auth routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [MycartAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [MycartAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [MycartAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [MycartAuthController::class, 'register'])->name('register.post');
});