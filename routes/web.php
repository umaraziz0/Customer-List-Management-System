<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix("admin")->group(function () {
    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get("/{any?}", [AdminController::class, 'index'])->where("any", ".*")->name("admin.home");
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get("/customers", [CustomerController::class, 'index']);
});
