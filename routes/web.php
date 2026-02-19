<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $courses = \App\Models\ShortCourse::where('status', true)->get();
    return view('welcome', compact('courses'));
});

Auth::routes();

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

Route::middleware(['auth'])->group(function () {
    Route::get('/apply', [App\Http\Controllers\ApplicationController::class, 'showForm'])->name('apply');
    Route::post('/apply', [App\Http\Controllers\ApplicationController::class, 'store'])->name('apply.store');
    Route::get('/applications/{ref}/review', [App\Http\Controllers\ApplicationController::class, 'review'])->name('applications.review');
    Route::get('/applications/{ref}/admission/view', [App\Http\Controllers\ApplicationController::class, 'viewAdmission'])->name('applications.view-admission');
    Route::get('/applications/{ref}/admission', [App\Http\Controllers\ApplicationController::class, 'downloadAdmission'])->name('applications.download-admission');
    Route::get('/status', [App\Http\Controllers\ApplicationController::class, 'statusForm'])->name('status');
    Route::post('/status', [App\Http\Controllers\ApplicationController::class, 'checkStatus'])->name('status.check');
    Route::post('/applications/upload-receipt', [App\Http\Controllers\ApplicationController::class, 'uploadReceipt'])->name('applications.upload-receipt');
    Route::get('/payment-procedure/download', [App\Http\Controllers\ApplicationController::class, 'downloadPaymentProcedure'])->name('payment-procedure.download');
    
    Route::get('/password/change', [App\Http\Controllers\HomeController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/change', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('password.update');
});

Route::post('/payments/remita/init', [App\Http\Controllers\PaymentController::class, 'init'])->name('payments.remita.init');
Route::any('/payments/remita/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('payments.remita.callback');

Route::get('/applications/{ref}/payment/receipt', [App\Http\Controllers\PaymentController::class, 'viewReceipt'])->name('applications.payment.receipt.view');
Route::delete('/applications/{ref}/payment/receipt', [App\Http\Controllers\PaymentController::class, 'deleteReceipt'])->name('applications.payment.receipt.delete');

Route::get('/applications/{ref}/payment', [App\Http\Controllers\PaymentController::class, 'instruction'])->name('applications.payment');
Route::get('/applications/{ref}/confirm', [App\Http\Controllers\PaymentController::class, 'confirmForm'])->name('applications.confirm');
Route::post('/applications/{ref}/confirm', [App\Http\Controllers\PaymentController::class, 'processConfirmation'])->name('applications.confirm.process');

Route::get('/receipt/{ref}/view', [App\Http\Controllers\ReceiptController::class, 'view'])->name('receipt.view');
Route::get('/receipt/{ref}', [App\Http\Controllers\ReceiptController::class, 'download'])->name('receipt.download');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
    Route::get('/applications', [App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('applications.index');
    Route::patch('/applications/{id}/status', [App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::get('/applications/{id}/view-ssce', [App\Http\Controllers\Admin\ApplicationController::class, 'viewSsce'])->name('applications.view-ssce');
    Route::get('/applications/{id}/download-ssce', [App\Http\Controllers\Admin\ApplicationController::class, 'downloadSsce'])->name('applications.download-ssce');
    Route::get('/applications/{id}/view-receipt', [App\Http\Controllers\Admin\ApplicationController::class, 'viewReceipt'])->name('applications.view-receipt');
    Route::post('/applications/{id}/approve-payment', [App\Http\Controllers\Admin\ApplicationController::class, 'approvePayment'])->name('applications.approve-payment');
    
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');

    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    Route::delete('/applications/batch-destroy', [App\Http\Controllers\Admin\ApplicationController::class, 'batchDestroy'])->name('applications.batch-destroy');
});
