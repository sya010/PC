<?php

use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', App\Livewire\Home::class)->name('home');

Route::get('/shop', App\Livewire\Shop::class)->name('shop');

Route::get('/cart', App\Livewire\Cart::class)->name('cart');
Route::get('/delivery', App\Livewire\Cart\DeliveryView::class)->name('delivery');
Route::get('/checkout', App\Livewire\Cart\CheckoutView::class)->name('checkout');

// Product Detail Route
Route::get('/product/{id}', App\Livewire\ProductView::class)->name('product.view');

// Build PC route
Route::get('/build-pc', App\Livewire\PcBuilder::class)->name('build-pc');

Route::get('/build-pc/select/{type}', App\Livewire\ComponentSelector::class)->name('pc.select');

// Auth Routes
Route::get('/login', App\Livewire\Auth\Login::class)->name('login');
Route::get('/register', App\Livewire\Auth\Register::class)->name('register');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// User Routes
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', App\Livewire\User\Orders::class)->name('my-orders');
    Route::get('/my-profile', App\Livewire\User\Profile::class)->name('profile');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/products', App\Livewire\Admin\Products::class)->name('products');
    Route::get('/products/create', App\Livewire\Admin\ProductForm::class)->name('products.create');
    Route::get('/products/{id}/edit', App\Livewire\Admin\ProductForm::class)->name('products.edit');
    Route::get('/orders', App\Livewire\Admin\Orders::class)->name('orders');
    Route::get('/orders/{id}', App\Livewire\Admin\OrderView::class)->name('orders.view');
    Route::get('/users', App\Livewire\Admin\Users::class)->name('users');
    Route::get('/users/{id}', App\Livewire\Admin\UserView::class)->name('users.view');
    Route::get('/users/{id}/edit', App\Livewire\Admin\UserForm::class)->name('users.edit');
});

// Compare route
Route::get('/compare', function () {
    return view('compare');
})->name('compare');

// Support route
Route::get('/support', function () {
    return view('support');
})->name('support');

// About route
Route::get('/about', function () {
    return view('about');
})->name('about');

// Contact route
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Locale switching
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

