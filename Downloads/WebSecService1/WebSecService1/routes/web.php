<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserCreditController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductLikeController;
use App\Http\Controllers\GoogleController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Controllers\Admin\OrderController;

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::post('logout', [UsersController::class, 'doLogout'])->name('logout');
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('users/profile/{user?}', [UsersController::class, 'profile'])->name('users.profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
})->name('multiplication-table');

Route::get('/even', function () {
    return view('even');
})->name('even-numbers');

Route::get('/prime', function () {
    return view('prime');
})->name('prime-numbers');

Route::get('/test', function () {
    return view('test');
});

Route::middleware(['auth'])->group(function () {
    // Product routes
    Route::get('/products', [ProductController::class, 'index'])->middleware('auth')->name('products.index');

    // Employee routes for product management
    Route::middleware(['can:manage-products'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/hold', [ProductController::class, 'toggleHold'])
            ->name('products.hold')
            ->middleware('can:hold_products');
    });

    // This needs to be after the create route
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Purchase routes
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::post('/products/{product}/purchase', [PurchaseController::class, 'store'])->name('purchases.store');

    // Credit management routes
    Route::middleware(['can:manage-customer-credits'])->group(function () {
        Route::get('/users/{user}/credits', [UserCreditController::class, 'show'])->name('credits.show');
        Route::put('/users/{user}/credits', [UserCreditController::class, 'update'])->name('credits.update');
    });

    // User Management Routes
    Route::get('/users/manage', [UsersController::class, 'manageUsers'])->name('users.manage');
    Route::post('/users/{user}/manage-credit', [UsersController::class, 'manageCredit'])->name('users.manage-credit');

    // Employee Management Routes
    Route::middleware(['auth', 'can:manage-employees'])->group(function () {
        Route::resource('employees', EmployeeController::class);
    });

    // Product like routes
    Route::post('/products/{product}/like', [ProductLikeController::class, 'toggleLike'])
        ->name('products.like')
        ->middleware('auth');

    // Checkout routes
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
});

// Google Authentication Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Email Verification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('products.index')->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Protected Routes that require email verification
Route::middleware(['auth'])->group(function () {
    // Product routes
    Route::get('/products', [ProductController::class, 'index'])->middleware('auth')->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Purchase routes
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::post('/products/{product}/purchase', [PurchaseController::class, 'store'])->name('purchases.store');

    // Product like routes
    Route::post('/products/{product}/like', [ProductLikeController::class, 'toggleLike'])
        ->name('products.like');

    // Employee routes for product management
    Route::middleware(['can:manage-products'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/hold', [ProductController::class, 'toggleHold'])
            ->name('products.hold')
            ->middleware('can:hold_products');
    });

    // Credit management routes
    Route::middleware(['can:manage-customer-credits'])->group(function () {
        Route::get('/users/{user}/credits', [UserCreditController::class, 'show'])->name('credits.show');
        Route::put('/users/{user}/credits', [UserCreditController::class, 'update'])->name('credits.update');
    });

    // User Management Routes
    Route::get('/users/manage', [UsersController::class, 'manageUsers'])->name('users.manage');
    Route::post('/users/{user}/manage-credit', [UsersController::class, 'manageCredit'])->name('users.manage-credit');

    // Employee Management Routes
    Route::middleware(['can:manage-employees'])->group(function () {
        Route::resource('employees', EmployeeController::class);
    });
});

Route::post('/cart/buy-now/{product}', [\App\Http\Controllers\CartController::class, 'buyNow'])->name('cart.buyNow');
Route::post('/cart/add/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'show'])->name('cart.show');
Route::post('/cart/remove/{product}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update-qty/{product}', [\App\Http\Controllers\CartController::class, 'updateQty'])->name('cart.updateQty');

Route::get('/purchases/{purchase}/tracking', [\App\Http\Controllers\PurchaseController::class, 'tracking'])->name('purchases.tracking');
Route::middleware(['role:admin|driver'])->group(function () {
    Route::post('/purchases/{purchase}/update-status', [\App\Http\Controllers\PurchaseController::class, 'updateStatus'])->name('purchases.updateStatus');
});

// Admin Routes
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
});

// Debug routes to check storage
Route::get('/debug-storage', function () {
    if (!app()->environment('production')) {
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');
        $productsPath = storage_path('app/public/products');

        $output = [
            'public_storage_exists' => file_exists($publicPath),
            'public_storage_is_link' => is_link($publicPath),
            'public_storage_target' => is_link($publicPath) ? readlink($publicPath) : null,
            'storage_path_exists' => file_exists($storagePath),
            'products_path_exists' => file_exists($productsPath),
        ];

        if (file_exists($productsPath)) {
            $files = scandir($productsPath);
            $output['product_files'] = array_filter($files, function($file) {
                return !in_array($file, ['.', '..']);
            });
        }

        return response()->json($output);
    }

    return redirect('/');
});

// Debug route for image upload testing
Route::match(['get', 'post'], '/debug-image-upload', [ProductController::class, 'debugImageUpload']);

// Debug form for testing image uploads
Route::get('/debug-image-form', function() {
    if (!app()->environment('production')) {
        return view('debug.image-form');
    }
    return redirect('/');
});

