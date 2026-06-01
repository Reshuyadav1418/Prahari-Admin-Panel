<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\UserController;
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('signin', [AuthController::class, 'signin'])->name('signin');
Route::post('verifyLogin', [AuthController::class, 'verifyLoginCredentials'])->name('verifyLoginCredentials');
Route::get('signup', [AuthController::class, 'signup'])->name('signup');
Route::post('store', [AuthController::class, 'store'])->name('store');

Route::group(['prefix' => 'account', 'middleware' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('userDashboard', [DashboardController::class, 'userDashboard'])->name('userDashboard');
    Route::get('adminDashboard',[DashboardController::class,'adminDashboard'])->name('adminDashboard');
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        // Route::get('/', [UserController::class, 'index'])->name('index');
        // Route::get('/transactions',[UserController::class,'getTransaction'])->name('getTransaction');
        // Route::post('/', [UserController::class, 'store'])->name('store');
        // Route::get('/{user_id}', [UserController::class, 'edit'])->name('edit');
        // Route::put('update/{user_id}', [UserController::class, 'update'])->name('update');
        // Route::delete('/{user_id}', [UserController::class, 'destroy'])->name('delete');
    });

    Route::group(['prefix'=>'admin','as'=>'admin.'],function(){
        Route::get('/',[AdminController::class,'cases'])->name('cases');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/upload', [AdminController::class, 'uploadProfileImage'])->name('profile.upload');
        Route::post('/profile/remove', [AdminController::class, 'removeProfileImage'])->name('profile.remove');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::post('/categories/store', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{id}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
        Route::get('/challan', [AdminController::class, 'challan'])->name('challan');
        Route::delete('/challan/{id}', [AdminController::class, 'destroyChallan'])->name('challan.destroy');
        Route::post('/challan/{id}/mark-paid', [AdminController::class, 'markChallanPaid'])->name('challan.markPaid');
        Route::post('/challan/{id}/mark-pending', [AdminController::class, 'markChallanPending'])->name('challan.markPending');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/data', [AdminController::class, 'getReportData'])->name('reports.data');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings/save', [AdminController::class, 'saveSettings'])->name('settings.save');
        Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        Route::post('/payments/store', [AdminController::class, 'storePayment'])->name('payments.store');
        Route::delete('/payments/{id}', [AdminController::class, 'destroyPayment'])->name('payments.destroy');
        Route::post('/payments/{id}/approve', [AdminController::class, 'approvePayment'])->name('payments.approve');
        Route::post('/payments/{id}/reject', [AdminController::class, 'rejectPayment'])->name('payments.reject');
        Route::post('/cases/{id}/status', [AdminController::class, 'updateCaseStatus'])->name('cases.updateStatus');
        Route::get('/praharis', [AdminController::class, 'praharis'])->name('praharis');
        Route::post('/praharis/store', [AdminController::class, 'storePrahari'])->name('praharis.store');
        Route::get('/praharis/{id}/edit', [AdminController::class, 'editPrahari'])->name('praharis.edit');
        Route::put('/praharis/{id}', [AdminController::class, 'updatePrahari'])->name('praharis.update');
        Route::delete('/praharis/{id}', [AdminController::class, 'destroyPrahari'])->name('praharis.destroy');
        Route::get('/cases/{id}/view', [AdminController::class, 'showCase'])->name('cases.show');
        Route::post('/cases/store', [AdminController::class, 'storeCase'])->name('cases.store');
        Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
        Route::post('/cases/{id}/approve', [AdminController::class, 'approveCase'])->name('cases.approve');
        Route::post('/cases/{id}/reject', [AdminController::class, 'rejectCase'])->name('cases.reject');
        Route::delete('/cases/{id}', [AdminController::class, 'deleteCase'])->name('cases.delete');
        Route::delete('/challan/{id}/record', [AdminController::class, 'deleteChallanRecord'])->name('challan.deleteRecord');
        Route::get('/admins', [AdminController::class, 'admins'])->name('admins');
        Route::get('/admins/data', [AdminController::class, 'getAdminsData'])->name('admins.data');
        Route::post('/admins/store', [AdminController::class, 'storeAdmin'])->name('admins.store');
        Route::get('/admins/{id}/edit', [AdminController::class, 'editAdmin'])->name('admins.edit');
        Route::put('/admins/{id}', [AdminController::class, 'updateAdmin'])->name('admins.update');
        Route::post('/admins/{id}', [AdminController::class, 'updateAdmin'])->name('admins.post-update');
        Route::delete('/admins/{id}', [AdminController::class, 'deleteAdmin'])->name('admins.delete');
    });
});







