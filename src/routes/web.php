<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\RatingController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopManagerController;
use App\Http\Controllers\CheckoutController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/shops/search', [ShopController::class, 'search'])->name('shops.search');
Route::get('/shops/detail/{id}',  [ShopController::class, 'show'])->name('shops.detail');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'register'])->name('register.post');
Route::get('/thanks', [RegisteredUserController::class, 'showThanks'])->name('thanks');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'show'])->name('mypage');

    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservation.submit');
    Route::get('/reservation/{id}', [ReservationController::class, 'show'])->name('reservation.show');

    Route::get('/reservations/{id}', [ReservationController::class, 'detail'])->name('reservations.detail');

    Route::get('/done', [ReservationController::class, 'done'])->name('done');
    Route::post('/reservation/finalize', [ReservationController::class, 'finalize'])->name('reservation.finalize');
    Route::get('/reservation/clear/{id}', [ReservationController::class, 'clearSession'])->name('reservation.clear');

    Route::get('/reservations/{id}/edit', [ReservationController::class, 'edit'])->name('reservation.edit');
    Route::patch('/reservation/{id}', [ReservationController::class, 'update'])->name('reservation.update');
    Route::delete('/reservation/{id}', [ReservationController::class, 'delete'])->name('reservation.delete');

    Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    Route::get('/mypage', [RatingController::class, 'showMypage'])->name('mypage');
    Route::post('/ratings/store', [RatingController::class, 'store'])->name('ratings.store');

    Route::post('/create-checkout-session', [CheckoutController::class, 'createCheckoutSession'])->name('create-checkout-session');
    Route::get('/pay', [CheckoutController::class, 'showPayForm'])->name('pay.form');

    Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/shop-managers', [AdminController::class, 'createShopManager'])->name('admin.createShopManager');
});

Route::middleware(['auth', 'role:shop_manager'])->group(function () {
    Route::get('/shop-manager', [ShopManagerController::class, 'index'])->name('shopManager.dashboard');
    Route::get('/shop-manager/shops', [ShopManagerController::class, 'showShops'])->name('shopManager.shops');
    Route::post('/shop-manager/shops', [ShopManagerController::class, 'storeShop'])->name('shopManager.storeShop');
    Route::get('/shop-manager/shops/{id}/edit', [ShopManagerController::class, 'editShop'])->name('shopManager.edit_shop');
    Route::put('/shop-manager/shops/{id}', [ShopManagerController::class, 'updateShop'])->name('shopManager.updateShop');
    Route::get('/shop-manager/reservations', [ShopManagerController::class, 'showReservations'])->name('shopManager.reservations');
    Route::post('/shop-manager/send-notification-mail', [ShopManagerController::class, 'sendNotificationMail'])->name('shopManager.sendNotificationMail');

    Route::get('/shop-manager/scan', [ShopManagerController::class, 'showScanForm'])->name('shopManager.scan.form');
    Route::post('/shop-manager/scan', [ShopManagerController::class, 'scanQrCode'])->name('shopManager.scan.qrcode');
    Route::post('/shopManager/confirmReservation/{id}', [ShopManagerController::class, 'confirmReservation'])->name('shopManager.confirmReservation');
    Route::post('/shopManager/generateQrCode/{id}', [ShopManagerController::class, 'generateQrCode'])->name('shopManager.generateQrCode');
});


Route::get('/email/verify', function () {
    return view('verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '確認リンクを再送信しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');