<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LetterAgreementController;
use App\Http\Controllers\OfferingLetterController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\PurposeController;
use App\Http\Controllers\Select2Controller;
use App\Models\LetterAgreement;
use App\Models\OfferingLetter;

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
        Route::get('project', [Select2Controller::class, 'project']);
        Route::get('bank', [Select2Controller::class, 'bank']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('datatable',[DashboardController::class, 'datatable']);
        Route::get('download/{code}',[DashboardController::class, 'download']);
    });

    Route::prefix('persetujuan')->group(function () {
        Route::get('/', [ApprovalController::class, 'index']);
        Route::get('datatable',[ApprovalController::class, 'datatable']);
        Route::post('get_count_approval',[ApprovalController::class, 'getCountApproval']);
        Route::post('approve',[ApprovalController::class, 'approve']);
        Route::get('detail/{id}',[ApprovalController::class, 'detail']);
    });

    Route::prefix('proyek')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[ProjectController::class, 'index']);
        Route::get('datatable',[ProjectController::class, 'datatable']);
        Route::post('create',[ProjectController::class, 'create']);
        Route::post('show',[ProjectController::class, 'show']);
        Route::post('destroy',[ProjectController::class, 'destroy']);
    });

    Route::prefix('surat_penawaran')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[OfferingLetterController::class, 'index']);
        Route::get('datatable',[OfferingLetterController::class, 'datatable']);
        Route::post('create',[OfferingLetterController::class, 'create']);
        Route::post('show',[OfferingLetterController::class, 'show']);
        Route::post('detail',[OfferingLetterController::class, 'detail']);
        Route::post('destroy',[OfferingLetterController::class, 'destroy']);
        Route::get('print/{id}',[OfferingLetterController::class, 'print']);
    });

    Route::prefix('spk')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[LetterAgreementController::class, 'index']);
        Route::get('datatable',[LetterAgreementController::class, 'datatable']);
        Route::post('create',[LetterAgreementController::class, 'create']);
        Route::post('show',[LetterAgreementController::class, 'show']);
        Route::post('detail',[LetterAgreementController::class, 'detail']);
        Route::post('destroy',[LetterAgreementController::class, 'destroy']);
        Route::get('print/{id}',[LetterAgreementController::class, 'print']);
    });

    Route::prefix('invoice')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[InvoiceController::class, 'index']);
        Route::get('datatable',[InvoiceController::class, 'datatable']);
        Route::post('create',[InvoiceController::class, 'create']);
        Route::post('create_receipt',[InvoiceController::class, 'createReceipt']);
        Route::post('show',[InvoiceController::class, 'show']);
        Route::post('detail',[InvoiceController::class, 'detail']);
        Route::post('destroy',[InvoiceController::class, 'destroy']);
        Route::get('print/{id}',[InvoiceController::class, 'print']);
        Route::get('print_receipt/{id}',[InvoiceController::class, 'printReceipt']);
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

    Route::prefix('rekening_bank')->middleware('admin.auth:1')->group(function () {
        Route::get('/',[BankController::class, 'index']);
        Route::get('datatable',[BankController::class, 'datatable']);
        Route::post('create',[BankController::class, 'create']);
        Route::post('show',[BankController::class, 'show']);
        Route::post('destroy',[BankController::class, 'destroy']);
    });
});