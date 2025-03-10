<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DrafterController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LegalityController;
use App\Http\Controllers\MitigationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportPaymentController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\RevisionDrafterController;
use App\Http\Controllers\TmNewController;
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
use App\Http\Controllers\SurveyResultController;
use App\Http\Controllers\SurveyDocumentationController;
use App\Http\Controllers\ItemSurveyController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\AndalalinController;
use App\Http\Controllers\HearingController;
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
        Route::get('employee', [Select2Controller::class, 'employee']);
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

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::post('update_password',[ProfileController::class, 'updatePassword']);
    });

    Route::prefix('persetujuan')->group(function () {
        Route::get('/', [ApprovalController::class, 'index']);
        Route::get('datatable',[ApprovalController::class, 'datatable']);
        Route::post('get_count_approval',[ApprovalController::class, 'getCountApproval']);
        Route::post('approve',[ApprovalController::class, 'approve']);
        Route::get('detail/{id}',[ApprovalController::class, 'detail']);
    });

    Route::prefix('notifikasi')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('datatable',[NotificationController::class, 'datatable']);
        Route::post('get_notification',[NotificationController::class, 'getNotification']);
    });

    Route::prefix('proyek')->middleware('admin.auth:01,02,06,07,08,09')->group(function () {
        Route::get('/',[ProjectController::class, 'index']);
        Route::get('datatable',[ProjectController::class, 'datatable']);
        Route::post('create',[ProjectController::class, 'create']);
        Route::post('show',[ProjectController::class, 'show']);
        Route::post('destroy',[ProjectController::class, 'destroy']);
        Route::post('recap',[ProjectController::class, 'recap']);
        Route::post('done',[ProjectController::class, 'done']);
    });

    Route::prefix('surat_penawaran')->middleware('admin.auth:01,02,06,07,08,09')->group(function () {
        Route::get('/',[OfferingLetterController::class, 'index']);
        Route::get('datatable',[OfferingLetterController::class, 'datatable']);
        Route::post('create',[OfferingLetterController::class, 'create']);
        Route::post('show',[OfferingLetterController::class, 'show']);
        Route::post('detail',[OfferingLetterController::class, 'detail']);
        Route::post('destroy',[OfferingLetterController::class, 'destroy']);
        Route::get('print/{id}',[OfferingLetterController::class, 'print']);
    });

    Route::prefix('cuti')->middleware('admin.auth:01,07,08')->group(function () {
        Route::get('/',[LeaveController::class, 'index']);
        Route::get('datatable',[LeaveController::class, 'datatable']);
        Route::post('create',[LeaveController::class, 'create']);
        Route::post('show',[LeaveController::class, 'show']);
        Route::post('detail',[LeaveController::class, 'detail']);
        Route::post('destroy',[LeaveController::class, 'destroy']);
    });

    Route::prefix('spk')->middleware('admin.auth:01,02,06,07,08,09')->group(function () {
        Route::get('/',[LetterAgreementController::class, 'index']);
        Route::get('datatable',[LetterAgreementController::class, 'datatable']);
        Route::post('create',[LetterAgreementController::class, 'create']);
        Route::post('show',[LetterAgreementController::class, 'show']);
        Route::post('detail',[LetterAgreementController::class, 'detail']);
        Route::post('destroy',[LetterAgreementController::class, 'destroy']);
        Route::get('print/{id}',[LetterAgreementController::class, 'print']);
    });

    Route::prefix('hasil_survei')->middleware('admin.auth:01,05,06,07,08,09,10,11,12')->group(function () {
        Route::get('/',[SurveyResultController::class, 'index']);
        Route::get('datatable',[SurveyResultController::class, 'datatable']);
        Route::post('create',[SurveyResultController::class, 'create']);
        Route::post('show',[SurveyResultController::class, 'show']);
        Route::post('upload',[SurveyResultController::class, 'upload']);
        Route::post('check',[SurveyResultController::class, 'check']);
        Route::post('show_upload',[SurveyResultController::class, 'showUpload']);
        Route::post('destroy_file',[SurveyResultController::class, 'destroyFile']);
        Route::post('destroy',[SurveyResultController::class, 'destroy']);
        Route::get('print/{id}',[SurveyResultController::class, 'print']);
    });

    Route::prefix('item_survei')->middleware('admin.auth:01,05,06,07,08,09,10,11,12')->group(function () {
        Route::get('/',[ItemSurveyController::class, 'index']);
        Route::get('datatable',[ItemSurveyController::class, 'datatable']);
        Route::post('create',[ItemSurveyController::class, 'create']);
        Route::post('show',[ItemSurveyController::class, 'show']);
        Route::post('upload',[ItemSurveyController::class, 'upload']);
        Route::post('check',[ItemSurveyController::class, 'check']);
        Route::post('show_upload',[ItemSurveyController::class, 'showUpload']);
        Route::post('destroy_file',[ItemSurveyController::class, 'destroyFile']);
        Route::post('destroy',[ItemSurveyController::class, 'destroy']);
        Route::get('print/{id}',[ItemSurveyController::class, 'print']);
    });

    Route::prefix('dokumentasi_survei')->middleware('admin.auth:01,05,06,07,08,09,10,11,12')->group(function () {
        Route::get('/',[SurveyDocumentationController::class, 'index']);
        Route::get('datatable',[SurveyDocumentationController::class, 'datatable']);
        Route::post('create',[SurveyDocumentationController::class, 'create']);
        Route::post('show',[SurveyDocumentationController::class, 'show']);
        Route::post('upload',[SurveyDocumentationController::class, 'upload']);
        Route::post('check',[SurveyDocumentationController::class, 'check']);
        Route::post('show_upload',[SurveyDocumentationController::class, 'showUpload']);
        Route::post('destroy_file',[SurveyDocumentationController::class, 'destroyFile']);
        Route::post('destroy',[SurveyDocumentationController::class, 'destroy']);
        Route::get('print/{id}',[SurveyDocumentationController::class, 'print']);
    });

    Route::prefix('kelengkapan_dokumen')->middleware('admin.auth:01,02,06,07,08,09')->group(function () {
        Route::get('/',[DocumentationController::class, 'index']);
        Route::get('datatable',[DocumentationController::class, 'datatable']);
        Route::post('create',[DocumentationController::class, 'create']);
        Route::post('create_receipt',[DocumentationController::class, 'createReceipt']);
        Route::post('show',[DocumentationController::class, 'show']);
        Route::post('detail',[DocumentationController::class, 'detail']);
        Route::post('destroy',[DocumentationController::class, 'destroy']);
        Route::post('upload',[DocumentationController::class, 'upload']);
        Route::post('check',[DocumentationController::class, 'check']);
        Route::post('show_upload',[DocumentationController::class, 'showUpload']);
        Route::post('destroy_file',[DocumentationController::class, 'destroyFile']);
    });

    Route::prefix('disposisi_legalitas')->middleware('admin.auth:01,02,06,07,08,09,12')->group(function () {
        Route::get('/',[LegalityController::class, 'index']);
        Route::get('datatable',[LegalityController::class, 'datatable']);
        Route::post('create',[LegalityController::class, 'create']);
        Route::post('create_receipt',[LegalityController::class, 'createReceipt']);
        Route::post('show',[LegalityController::class, 'show']);
        Route::post('detail',[LegalityController::class, 'detail']);
        Route::post('destroy',[LegalityController::class, 'destroy']);
        Route::post('upload',[LegalityController::class, 'upload']);
        Route::post('check',[LegalityController::class, 'check']);
        Route::post('show_upload',[LegalityController::class, 'showUpload']);
        Route::post('destroy_file',[LegalityController::class, 'destroyFile']);
    });

    Route::prefix('mitigasi')->middleware('admin.auth:01,02,06,07,08,09,12')->group(function () {
        Route::get('/',[MitigationController::class, 'index']);
        Route::get('datatable',[MitigationController::class, 'datatable']);
        Route::post('create',[MitigationController::class, 'create']);
        Route::post('create_receipt',[MitigationController::class, 'createReceipt']);
        Route::post('show',[MitigationController::class, 'show']);
        Route::post('detail',[MitigationController::class, 'detail']);
        Route::post('destroy',[MitigationController::class, 'destroy']);
        Route::post('upload',[MitigationController::class, 'upload']);
        Route::post('check',[MitigationController::class, 'check']);
        Route::post('show_upload',[MitigationController::class, 'showUpload']);
        Route::post('destroy_file',[MitigationController::class, 'destroyFile']);
    });

    Route::prefix('berita_acara')->middleware('admin.auth:01,02,06,07,08,09,12')->group(function () {
        Route::get('/',[TmNewController::class, 'index']);
        Route::get('datatable',[TmNewController::class, 'datatable']);
        Route::post('create',[TmNewController::class, 'create']);
        Route::post('create_receipt',[TmNewController::class, 'createReceipt']);
        Route::post('show',[TmNewController::class, 'show']);
        Route::post('detail',[TmNewController::class, 'detail']);
        Route::post('destroy',[TmNewController::class, 'destroy']);
        Route::post('upload',[TmNewController::class, 'upload']);
        Route::post('check',[TmNewController::class, 'check']);
        Route::post('show_upload',[TmNewController::class, 'showUpload']);
        Route::post('destroy_file',[TmNewController::class, 'destroyFile']);
    });

    Route::prefix('drafter')->middleware('admin.auth:01,04,11,06,07,08')->group(function () {
        Route::get('/',[DrafterController::class, 'index']);
        Route::get('datatable',[DrafterController::class, 'datatable']);
        Route::post('create',[DrafterController::class, 'create']);
        Route::post('show',[DrafterController::class, 'show']);
        Route::post('detail',[DrafterController::class, 'detail']);
        Route::post('destroy',[DrafterController::class, 'destroy']);
        Route::post('upload',[DrafterController::class, 'upload']);
        Route::post('check',[DrafterController::class, 'check']);
        Route::post('show_upload',[DrafterController::class, 'showUpload']);
        Route::post('destroy_file',[DrafterController::class, 'destroyFile']);
    });

    Route::prefix('revisi_drafter')->middleware('admin.auth:01,04,11,06,07,08')->group(function () {
        Route::get('/',[RevisionDrafterController::class, 'index']);
        Route::get('datatable',[RevisionDrafterController::class, 'datatable']);
        Route::post('create',[RevisionDrafterController::class, 'create']);
        Route::post('show',[RevisionDrafterController::class, 'show']);
        Route::post('detail',[RevisionDrafterController::class, 'detail']);
        Route::post('destroy',[RevisionDrafterController::class, 'destroy']);
        Route::post('upload',[RevisionDrafterController::class, 'upload']);
        Route::post('check',[RevisionDrafterController::class, 'check']);
        Route::post('show_upload',[RevisionDrafterController::class, 'showUpload']);
        Route::post('destroy_file',[RevisionDrafterController::class, 'destroyFile']);
    });

    Route::prefix('dokumen_andalalin')->middleware('admin.auth:01,03,04,06,07,08,11,12')->group(function () {
        Route::get('/',[AndalalinController::class, 'index']);
        Route::get('datatable',[AndalalinController::class, 'datatable']);
        Route::post('create',[AndalalinController::class, 'create']);
        Route::post('create_receipt',[AndalalinController::class, 'createReceipt']);
        Route::post('show',[AndalalinController::class, 'show']);
        Route::post('detail',[AndalalinController::class, 'detail']);
        Route::post('destroy',[AndalalinController::class, 'destroy']);
        Route::post('upload',[AndalalinController::class, 'upload']);
        Route::post('check',[AndalalinController::class, 'check']);
        Route::post('show_upload',[AndalalinController::class, 'showUpload']);
        Route::post('destroy_file',[AndalalinController::class, 'destroyFile']);
    });

    Route::prefix('revisi')->middleware('admin.auth:01,03,06,07,08,12')->group(function () {
        Route::get('/',[RevisionController::class, 'index']);
        Route::get('datatable',[RevisionController::class, 'datatable']);
        Route::post('create',[RevisionController::class, 'create']);
        Route::post('create_receipt',[RevisionController::class, 'createReceipt']);
        Route::post('show',[RevisionController::class, 'show']);
        Route::post('detail',[RevisionController::class, 'detail']);
        Route::post('destroy',[RevisionController::class, 'destroy']);
        Route::post('upload',[RevisionController::class, 'upload']);
        Route::post('check',[RevisionController::class, 'check']);
        Route::post('show_upload',[RevisionController::class, 'showUpload']);
        Route::post('destroy_file',[RevisionController::class, 'destroyFile']);
    });

    Route::prefix('sidang')->middleware('admin.auth:01,06,07,08,09,11,12')->group(function () {
        Route::get('/',[HearingController::class, 'index']);
        Route::get('datatable',[HearingController::class, 'datatable']);
        Route::post('create',[HearingController::class, 'create']);
        Route::post('show',[HearingController::class, 'show']);
        Route::post('detail',[HearingController::class, 'detail']);
        Route::post('destroy',[HearingController::class, 'destroy']);
    });

    Route::prefix('invoice')->middleware('admin.auth:01,02,06,07,08,09')->group(function () {
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

    Route::prefix('payroll')->middleware('admin.auth:01,07,08')->group(function () {
        Route::get('/',[PayrollController::class, 'index']);
        Route::get('datatable',[PayrollController::class, 'datatable']);
        Route::post('create',[PayrollController::class, 'create']);
        Route::post('send_email',[PayrollController::class, 'sendEmail']);
        Route::post('history', [PayrollController::class, 'history']);
    });

    Route::prefix('customer')->middleware('admin.auth:01,02,06,07,09')->group(function () {
        Route::get('/',[CustomerController::class, 'index']);
        Route::get('datatable',[CustomerController::class, 'datatable']);
        Route::post('create',[CustomerController::class, 'create']);
        Route::post('show',[CustomerController::class, 'show']);
        Route::post('destroy',[CustomerController::class, 'destroy']);
    });

    Route::prefix('user')->middleware('admin.auth:01,07,08')->group(function () {
        Route::get('/',[UserController::class, 'index']);
        Route::get('datatable',[UserController::class, 'datatable']);
        Route::post('update_password',[UserController::class, 'updatePassword']);
        Route::post('create',[UserController::class, 'create']);
        Route::post('show',[UserController::class, 'show']);
        Route::post('destroy',[UserController::class, 'destroy']);
    });

    Route::prefix('peruntukan')->middleware('admin.auth:01,07,08')->group(function () {
        Route::get('/',[PurposeController::class, 'index']);
        Route::get('datatable',[PurposeController::class, 'datatable']);
        Route::post('create',[PurposeController::class, 'create']);
        Route::post('show',[PurposeController::class, 'show']);
        Route::post('destroy',[PurposeController::class, 'destroy']);
    });

    Route::prefix('jenis_proyek')->middleware('admin.auth:01,02,06,07,09')->group(function () {
        Route::get('/',[ProjectTypeController::class, 'index']);
        Route::get('datatable',[ProjectTypeController::class, 'datatable']);
        Route::post('create',[ProjectTypeController::class, 'create']);
        Route::post('show',[ProjectTypeController::class, 'show']);
        Route::post('destroy',[ProjectTypeController::class, 'destroy']);
    });

    Route::prefix('rekening_bank')->middleware('admin.auth:01,02,06,07,09')->group(function () {
        Route::get('/',[BankController::class, 'index']);
        Route::get('datatable',[BankController::class, 'datatable']);
        Route::post('create',[BankController::class, 'create']);
        Route::post('show',[BankController::class, 'show']);
        Route::post('destroy',[BankController::class, 'destroy']);
    });

    Route::prefix('laporan_pembayaran')->middleware('admin.auth:01,06,07,08,09')->group(function () {
        Route::get('/', [ReportPaymentController::class, 'index']);
        Route::post('process',[ReportPaymentController::class, 'process']);
    });
});