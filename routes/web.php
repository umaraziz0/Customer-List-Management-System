<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, "index"]);

Auth::routes();

Route::view('/home', "home_old");

// Admin routes
Route::prefix("admin")->group(function () {
    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get("/getagents", [AgentController::class, "getAgents"]);
        Route::put("/assign/{customer}", [CustomerController::class, 'assignAgent']);

        Route::get("/customers", [CustomerController::class, 'index']);
        Route::post("/customer", [CustomerController::class, 'store']);
        Route::put("/customer/{customer}", [CustomerController::class, 'update']);
        Route::delete("/customer/{customer}", [CustomerController::class, 'destroy']);

        Route::get("/{any?}", [AdminController::class, 'index'])->where("any", ".*")->name("admin.home");
    });
});

// Agent routes
Route::prefix("agent")->group(function () {
    Route::middleware(['auth', 'is_agent'])->group(function () {
        Route::get("/customers", [CustomerController::class, 'getCustomersByAgent']);
        Route::get("/getmessages", [AgentController::class, 'getMessages']);
        Route::post("/followup", [CustomerController::class, 'followUp']);
        Route::put("/updatestatus/{customer}", [CustomerController::class, 'updateStatus']);

        Route::get("/{any?}", [AgentController::class, 'index'])->where("any", ".*")->name("agent.home");
    });
});

// Admin & agent routes
Route::middleware(['auth'])->group(function () {
    //
});
