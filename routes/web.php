<?php
// routes/web.php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminAuthorController;
use App\Http\Controllers\Admin\AdminPublisherController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================================
// DASHBOARD ROUTE
// ============================================================
Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

// ============================================================
// PUBLIC ROUTES
// ============================================================

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/switch-language/{locale}', [HomeController::class, 'switchLanguage'])->name('switch-language');
Route::post('/switch-theme/{theme}', [HomeController::class, 'switchTheme'])->name('switch-theme');
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribe'])->name('newsletter.subscribe');

// ============================================================
// BOOK ROUTES (Frontend)
// ============================================================
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book}/download', [BookController::class, 'download'])->name('books.download');
Route::get('/books/{book}/preview', [BookController::class, 'preview'])->name('books.preview');
Route::get('/books/{book}/read', [BookController::class, 'read'])->name('books.read');

// ============================================================
// CATEGORY ROUTES (Frontend)
// ============================================================
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// ============================================================
// AUTHOR ROUTES (Frontend)
// ============================================================
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');

// ============================================================
// CART ROUTES
// ============================================================
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

// ============================================================
// CUSTOMER CHAT ROUTES
// ============================================================
Route::middleware(['auth'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('/create', [ChatController::class, 'create'])->name('create');
    Route::post('/', [ChatController::class, 'store'])->name('store');
    Route::get('/{chat}', [ChatController::class, 'show'])->name('show');
    Route::post('/{chat}/send', [ChatController::class, 'sendMessage'])->name('send');
    Route::delete('/message/{message}', [ChatController::class, 'deleteMessage'])->name('message.delete');
    Route::get('/users', [ChatController::class, 'getUsers'])->name('users');
    Route::get('/unread/count', [ChatController::class, 'unreadCount'])->name('unread');
});

// ============================================================
// AUTH ROUTES
// ============================================================
require __DIR__.'/auth.php';

// ============================================================
// CUSTOMER ROUTES (Protected)
// ============================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/customer/dashboard', CustomerDashboardController::class)->name('customer.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

     // Purchased Books Routes
    Route::get('/profile/purchased-books', [ProfileController::class, 'purchasedBooks'])->name('profile.purchased-books');
    Route::get('/profile/read/{purchase}', [ProfileController::class, 'readBook'])->name('profile.read-book');
    Route::get('/profile/download/{purchase}', [ProfileController::class, 'downloadBook'])->name('profile.download-book');

    // Orders
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{book}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites/check/{book}', [FavoriteController::class, 'check'])->name('favorites.check');

    // Reviews
    Route::post('/books/{book}/reviews', [BookController::class, 'storeReview'])->name('books.reviews.store');

});

// ============================================================
// ADMIN ROUTES (Protected)
// ============================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {

    // ============ DASHBOARD ============
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/chart-data', [AdminController::class, 'chartData'])->name('dashboard.chart-data');
    Route::get('/switch-language/{locale}', [AdminController::class, 'switchLanguage'])->name('switch-language');

    // Admin chat routes
    Route::get('/chat', [AdminChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{chat}', [AdminChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chat}/send', [AdminChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/create', [AdminChatController::class, 'createChat'])->name('chat.create');
    Route::post('/chat/{chat}/archive', [AdminChatController::class, 'archive'])->name('chat.archive');
    Route::delete('/chat/{chat}', [AdminChatController::class, 'destroy'])->name('chat.destroy');

    // ============ PROFILE MANAGEMENT ============
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [AdminProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [AdminProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // ============ BOOKS MANAGEMENT ============
    Route::resource('books', AdminBookController::class);
    Route::post('books/{book}/toggle-status', [AdminBookController::class, 'toggleStatus'])->name('books.toggle-status');
    Route::post('books/bulk-status', [AdminBookController::class, 'bulkStatus'])->name('books.bulk-status');
    Route::patch('books/{id}/restore', [AdminBookController::class, 'restore'])->name('books.restore');
    Route::delete('books/{id}/force-delete', [AdminBookController::class, 'forceDelete'])->name('books.force-delete');
    Route::post('books/bulk-upload', [AdminBookController::class, 'bulkUpload'])->name('books.bulk-upload');
    Route::get('books/export', [AdminBookController::class, 'export'])->name('books.export');

    // ============ CATEGORIES MANAGEMENT ============
    Route::resource('categories', AdminCategoryController::class);
    Route::post('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::post('categories/bulk-status', [AdminCategoryController::class, 'bulkStatus'])->name('categories.bulk-status');
    Route::post('categories/reorder', [AdminCategoryController::class, 'reorder'])->name('categories.reorder');

    // ============ AUTHORS MANAGEMENT ============
    Route::resource('authors', AdminAuthorController::class);
    Route::post('authors/{author}/toggle-status', [AdminAuthorController::class, 'toggleStatus'])->name('authors.toggle-status');
    Route::post('authors/bulk-status', [AdminAuthorController::class, 'bulkStatus'])->name('authors.bulk-status');
    Route::post('authors/bulk-upload', [AdminAuthorController::class, 'bulkUpload'])->name('authors.bulk-upload');
    Route::get('authors/export', [AdminAuthorController::class, 'export'])->name('authors.export');
    Route::get('authors/export/pdf', [AdminAuthorController::class, 'exportPdf'])->name('authors.export-pdf');
    Route::post('authors/import', [AdminAuthorController::class, 'import'])->name('authors.import');

    // ============ PUBLISHERS MANAGEMENT ============
    Route::resource('publishers', AdminPublisherController::class);
    Route::post('publishers/{publisher}/toggle-status', [AdminPublisherController::class, 'toggleStatus'])->name('publishers.toggle-status');
    Route::post('publishers/bulk-status', [AdminPublisherController::class, 'bulkStatus'])->name('publishers.bulk-status');

    // ============ ORDERS MANAGEMENT ============
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::patch('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/update-payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // ============ CUSTOMERS MANAGEMENT ============
    Route::resource('customers', AdminCustomerController::class);
    Route::post('customers/{customer}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::post('customers/bulk-status', [AdminCustomerController::class, 'bulkStatus'])->name('customers.bulk-status');
    Route::post('customers/import', [AdminCustomerController::class, 'import'])->name('customers.import');
    Route::get('customers/export', [AdminCustomerController::class, 'export'])->name('customers.export');

    // ============ COUPONS MANAGEMENT ============
    Route::resource('coupons', AdminCouponController::class);
    Route::post('coupons/{coupon}/toggle-status', [AdminCouponController::class, 'toggleStatus'])->name('coupons.toggle-status');
    Route::post('coupons/bulk-status', [AdminCouponController::class, 'bulkStatus'])->name('coupons.bulk-status');
    Route::get('coupons/export', [AdminCouponController::class, 'export'])->name('coupons.export');

    // ============ BANNERS MANAGEMENT ============
    Route::resource('banners', AdminBannerController::class);
    Route::post('banners/{banner}/toggle-status', [AdminBannerController::class, 'toggleStatus'])->name('banners.toggle-status');
    Route::post('banners/bulk-status', [AdminBannerController::class, 'bulkStatus'])->name('banners.bulk-status');
    Route::post('banners/reorder', [AdminBannerController::class, 'reorder'])->name('banners.reorder');
    Route::get('banners/export', [AdminBannerController::class, 'export'])->name('banners.export');

    // ============ NOTIFICATIONS MANAGEMENT ============
     Route::get('notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
    Route::patch('notifications/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('notifications/{notification}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications/delete-all', [AdminNotificationController::class, 'deleteAll'])->name('notifications.delete-all');


    // ============ SETTINGS MANAGEMENT ============
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::get('/settings/{tab}', [AdminSettingsController::class, 'index'])->name('settings.tab');

    // General Settings
    Route::put('/settings/general', [AdminSettingsController::class, 'updateGeneral'])->name('settings.general');

    // Type Settings
    Route::post('/settings/types', [AdminSettingsController::class, 'storeType'])->name('settings.types.store');
    Route::put('/settings/types/{type}', [AdminSettingsController::class, 'updateType'])->name('settings.types.update');
    Route::delete('/settings/types/{type}', [AdminSettingsController::class, 'destroyType'])->name('settings.types.destroy');

    // Author Settings
    Route::post('/settings/authors', [AdminSettingsController::class, 'storeAuthor'])->name('settings.authors.store');
    Route::put('/settings/authors/{author}', [AdminSettingsController::class, 'updateAuthor'])->name('settings.authors.update');
    Route::delete('/settings/authors/{author}', [AdminSettingsController::class, 'destroyAuthor'])->name('settings.authors.destroy');

    // Publisher Settings
    Route::post('/settings/publishers', [AdminSettingsController::class, 'storePublisher'])->name('settings.publishers.store');
    Route::put('/settings/publishers/{publisher}', [AdminSettingsController::class, 'updatePublisher'])->name('settings.publishers.update');
    Route::delete('/settings/publishers/{publisher}', [AdminSettingsController::class, 'destroyPublisher'])->name('settings.publishers.destroy');

    // Print Settings
    Route::put('/settings/print', [AdminSettingsController::class, 'updatePrint'])->name('settings.print');

    // Order Settings
    Route::put('/settings/order', [AdminSettingsController::class, 'updateOrder'])->name('settings.order');

    // Discount Settings
    Route::put('/settings/discount', [AdminSettingsController::class, 'updateDiscount'])->name('settings.discount');

    // Coupon Settings
    Route::put('/settings/coupon', [AdminSettingsController::class, 'updateCoupon'])->name('settings.coupon');

    // Notification Settings
    Route::put('/settings/notification', [AdminSettingsController::class, 'updateNotification'])->name('settings.notification');
});

// ============================================================
// EXTRA ROUTE - Home (for auth redirect)
// ============================================================
Route::get('/home', fn () => redirect()->route('home'));
