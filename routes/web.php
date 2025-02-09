<?php

use Illuminate\Support\Facades\Route;

// Home Routes
use App\Http\Controllers\HomeController;

// User Routes
use App\Http\Controllers\User\UserController;

// Auth Routes
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\FollowController;
use App\Http\Controllers\Auth\ProfileController;

// Commerce Routes
use App\Http\Controllers\Commerce\WishlistController;
use App\Http\Controllers\Commerce\CartController;
use App\Http\Controllers\Commerce\CheckoutController;
use App\Http\Controllers\Commerce\ShopProfileController;
use App\Http\Controllers\Commerce\ProductController;
use App\Http\Controllers\Commerce\RatingController;

// Content Routes
use App\Http\Controllers\Content\PlaylistController;
use App\Http\Controllers\Content\PostController;
use App\Http\Controllers\Content\YoutubeController;

// Notification Routes
use App\Http\Controllers\Notification\NotificationController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/submit', [AuthController::class, 'register'])->name('register.submit');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Home Route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Post Routes
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'create'])->name('posts.create');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('/{id}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::get('/explore', [PostController::class, 'explore'])->name('posts.explore');
        Route::post('/{post}/like', [PostController::class, 'likePost'])->name('posts.like');
        Route::post('/{post}/comment', [PostController::class, 'commentOnPost'])->name('posts.comment');
    });

    // User Routes
    Route::get('/search-users', [UserController::class, 'searchUsers'])->name('search.users');
    Route::get('/profile/{name}', [UserController::class, 'show'])->name('profile.show');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    });

    // Follow Routes
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
    Route::post('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');

    // Notification Routes
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Shop Profile Routes
    Route::prefix('shop')->group(function () {
        Route::get('/create', [ShopProfileController::class, 'create'])->name('shop.profile.create');
        Route::post('/store', [ShopProfileController::class, 'store'])->name('shop.profile.store');
        Route::get('/otp', [ShopProfileController::class, 'otp'])->name('shop.profile.otp');
        Route::post('/verify', [ShopProfileController::class, 'verify'])->name('shop.profile.verify');
        Route::get('/profile', [ShopProfileController::class, 'index'])->name('shop.profile.index');
        Route::get('/{shop}/edit', [ShopProfileController::class, 'edit'])->name('shop.profile.edit');
        Route::put('/{shop}', [ShopProfileController::class, 'update'])->name('shop.profile.update');
    });

    // Product Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/search', [ProductController::class, 'search'])->name('products.search');
    });

    // Rating Routes
    Route::post('/products/{id}/rating', [RatingController::class, 'store'])->name('rating.store');
    Route::delete('/rating/{id}', [RatingController::class, 'destroy'])->name('rating.destroy');
    Route::post('/rating/{id}/respond', [RatingController::class, 'respond'])->name('rating.respond');

    // Wishlist Routes
    Route::prefix('wishlist')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/add/{productId}', [WishlistController::class, 'add'])->name('wishlist.add');
        Route::post('/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    });

    // Cart Routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add/{productId}', [CartController::class, 'add'])->name('cart.add');
        Route::post('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    });

    // Checkout Routes
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::post('/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');
        Route::get('/success', function () { return view('checkout.success'); })->name('checkout.success');
        Route::get('/pending', function () { return view('checkout.pending'); })->name('checkout.pending');
        Route::get('/failed', function () { return view('checkout.failed'); })->name('checkout.failed');
    });

    // Youtube Routes
    Route::prefix('youtube')->group(function () {
        Route::get('/', [YoutubeController::class, 'index'])->name('youtube.index');
        Route::get('/video/{videoId}', [YoutubeController::class, 'showVideoDetail'])->name('video.detail');
        Route::post('/video/add-to-playlist', [YoutubeController::class, 'addToPlaylist'])->name('video.addToPlaylist');
    });

    // Playlist Routes
    Route::prefix('playlists')->group(function () {
        Route::get('/', [PlaylistController::class, 'index'])->name('playlist.index');
        Route::get('/create', [PlaylistController::class, 'create'])->name('playlist.create');
        Route::post('/', [PlaylistController::class, 'store'])->name('playlist.store');
        Route::get('/{id}', [PlaylistController::class, 'show'])->name('playlist.show');
        Route::get('/{playlist}/edit', [PlaylistController::class, 'edit'])->name('playlists.edit');
        Route::put('/{playlist}', [PlaylistController::class, 'update'])->name('playlists.update');
        Route::delete('/{playlist}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
        Route::post('/{id}/add-video', [PlaylistController::class, 'addVideo'])->name('playlist.addVideo');
        Route::delete('/{playlist}/videos', [PlaylistController::class, 'removeAllVideos'])->name('playlist.removeAllVideos');
        Route::delete('/{playlist}/videos/remove', [PlaylistController::class, 'removeSelectedVideos'])->name('playlist.removeSelectedVideos');
    });

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
