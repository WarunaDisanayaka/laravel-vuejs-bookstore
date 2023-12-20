<?php

use App\Http\Controllers\Accountant\AccountantController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminMetricsController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserManageController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\AdminStoreController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\ProductListController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

//user rotues

Route::get('/', [UserController::class,'index'])->name('home');
Route::get('/dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //chekcout
    Route::prefix('checkout')->controller(CheckoutController::class)->group((function()  {
        Route::post('order','store')->name('checkout.store');
        Route::get('success','success')->name('checkout.success');
        Route::get('cancel','cancel')->name('checkout.cancel');
    }));

});

//add to cart

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('view','view')->name('cart.view');
    Route::post('store/{product}','store')->name('cart.store');
    Route::patch('update/{product}','update')->name('cart.update');
    Route::delete('delete/{product}','delete')->name('cart.delete');
});

//routes for products list and filter
Route::prefix('products')->controller(ProductListController::class)->group(function ()  {
    Route::get('/','index')->name('products.index');

});



//end

//admin routs

Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    //products routes
    Route::get('/products', [BookController::class, 'index'])->name('admin.products.index');
    Route::post('/products/store',[BookController::class,'store'])->name('admin.products.store');
    Route::put('/products/update/{id}',[BookController::class,'update'])->name('admin.products.update');
    Route::delete('/products/image/{id}',[BookController::class,'deleteImage'])->name('admin.products.image.delete');
    Route::delete('/products/destory/{id}',[BookController::class,'destory'])->name('admin.products.destory');

    Route::get('/users', [UserManageController::class, 'index'])->name('admin.users.index');
    Route::post('/users/store', [UserManageController::class, 'addUser'])->name('admin.users.store');

    // User Roles routes
    Route::get('/roles', [UserRoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/roles/store', [UserRoleController::class, 'store'])->name('admin.roles.store');
    Route::put('/roles/update/{id}', [UserRoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/destroy/{id}', [UserRoleController::class, 'destroy'])->name('admin.roles.destroy');


    // Store routes
    Route::get('/stores', [AdminStoreController::class, 'index'])->name('admin.stores.index');
    Route::post('/stores/store', [AdminStoreController::class, 'store'])->name('admin.stores.store');
    Route::put('/stores/update/{id}', [AdminStoreController::class, 'update'])->name('admin.stores.update');
    Route::delete('/stores/destroy/{id}', [AdminStoreController::class, 'destroy'])->name('admin.stores.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');


    // Metrics routes
    Route::get('/metrics/most-expensive-books', [AdminMetricsController::class, 'mostExpensiveBook'])->name('admin.metrics.most_expensive_book');
    Route::get('/metrics/most-popular-books-by-store', [AdminMetricsController::class, 'mostPopularBooksByStore'])->name('admin.metrics.most_popular_books_by_store');
    Route::get('/metrics/most-bought-book', [AdminMetricsController::class, 'mostBoughtBook'])->name('admin.metrics.most_bought_book');
    Route::get('/metrics/least-preferred-book', [AdminMetricsController::class, 'leastPreferredBook'])->name('admin.metrics.least_preferred_book');


    Route::get('/manager/dashboard', [ManagerController::class, 'view'])->name('manager.dashboard');

    //products routes
    Route::get('/manager/products', [ManagerController::class, 'index'])->name('manager.products.index');
    Route::post('/manager/products/store',[ManagerController::class,'store'])->name('manager.products.store');
    Route::put('/manager/products/update/{id}',[ManagerController::class,'update'])->name('manager.products.update');
    Route::delete('/manager/products/image/{id}',[ManagerController::class,'deleteImage'])->name('manager.products.image.delete');
    Route::delete('/manager/products/destory/{id}',[ManagerController::class,'destory'])->name('manager.products.destory');


    Route::get('/accountant/dashboard', [AccountantController::class, 'view'])->name('accountant.dashboard');
    Route::get('/orders/export', [OrderController::class, 'export'])->name('accountant.orders.export');


});

//end

require __DIR__ . '/auth.php';
