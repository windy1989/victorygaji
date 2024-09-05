<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\PurposeController;
use App\Http\Controllers\Select2Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */
Route::get('/', function () {
    return redirect('/login');
});

Route::prefix('login')->group(function () {
    Route::get('/', [AuthController::class, 'login']);
    Route::post('auth',[AuthController::class, 'auth']);
});

Route::prefix('logout')->group(function () {
    Route::get('/', [AuthController::class, 'logout']);
});

Route::middleware('login')->group(function () {
    
    Route::prefix('select2')->group(function() {
        Route::get('customer', [Select2Controller::class, 'customer']);
        Route::get('region', [Select2Controller::class, 'region']);
        Route::get('project_type', [Select2Controller::class, 'projectType']);
        Route::get('purpose', [Select2Controller::class, 'purpose']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('datatable',[DashboardController::class, 'datatable']);
        Route::get('download/{code}',[DashboardController::class, 'download']);
    });

    Route::prefix('proyek')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[ProjectController::class, 'index']);
        Route::get('datatable',[ProjectController::class, 'datatable']);
        Route::post('create',[ProjectController::class, 'create']);
        Route::post('show',[ProjectController::class, 'show']);
    });

    Route::prefix('payroll')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[PayrollController::class, 'index']);
        Route::get('datatable',[PayrollController::class, 'datatable']);
        Route::post('create',[PayrollController::class, 'create']);
        Route::post('send_email',[PayrollController::class, 'sendEmail']);
        Route::post('history', [PayrollController::class, 'history']);
    });

    Route::prefix('customer')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[CustomerController::class, 'index']);
        Route::get('datatable',[CustomerController::class, 'datatable']);
        Route::post('create',[CustomerController::class, 'create']);
        Route::post('show',[CustomerController::class, 'show']);
        Route::post('destroy',[CustomerController::class, 'destroy']);
    });

    Route::prefix('user')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[UserController::class, 'index']);
        Route::get('datatable',[UserController::class, 'datatable']);
        Route::post('update_password',[UserController::class, 'updatePassword']);
        Route::post('create',[UserController::class, 'create']);
        Route::post('show',[UserController::class, 'show']);
        Route::post('destroy',[UserController::class, 'destroy']);
    });

    Route::prefix('peruntukan')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[PurposeController::class, 'index']);
        Route::get('datatable',[PurposeController::class, 'datatable']);
        Route::post('create',[PurposeController::class, 'create']);
        Route::post('show',[PurposeController::class, 'show']);
        Route::post('destroy',[PurposeController::class, 'destroy']);
    });

    Route::prefix('jenis_proyek')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[ProjectTypeController::class, 'index']);
        Route::get('datatable',[ProjectTypeController::class, 'datatable']);
        Route::post('create',[ProjectTypeController::class, 'create']);
        Route::post('show',[ProjectTypeController::class, 'show']);
        Route::post('destroy',[ProjectTypeController::class, 'destroy']);
    });
});