<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
Route::post('/payment/retry', [PaymentController::class, 'retryPayment'])->name('payment.retry');
Route::post('/wallet/update', [WalletController::class, 'update'])->name('wallet.update');

Route::get('/payment/success', [RegisteredUserController::class, 'handlePaymentSuccess'])->name('payment.success');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

// Pending Requests Routes
Route::middleware('auth')->group(function () {
    Route::put('/wishlist/accept/{wishlistId}', [WishlistController::class, 'acceptRequest'])->name('wishlist.accept');
    Route::put('/wishlist/decline/{wishlistId}', [WishlistController::class, 'declineRequest'])->name('wishlist.decline');

});

Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');

Route::get('/wishlist/{userId}', [WishlistController::class, 'showWishlist'])->name('wishlist.show');

Route::post('/wishlist/add/{targetUserId}', [DashboardController::class, 'addToWishlist'])->name('wishlist.add');




Route::middleware(['auth'])->group(function () {
    Route::get('/chat/{user}', [MessageController::class, 'index'])->name('chat.index');
    Route::post('/chat/{user}', [MessageController::class, 'store'])->name('chat.store');
});
