<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PaymentInfoController;
use App\Http\Controllers\Admin\AdminPasswordController;
use App\Http\Controllers\Admin\InvestmentPlanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\Admin\PlanRequestAdminController;

// ── Public pages ──────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/plans', fn() => view('plans'))->name('plans');

// ── Customer (authenticated) ──────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/password/change', [ChangePasswordController::class, 'create'])->name('password.change');
    Route::post('/password/change', [ChangePasswordController::class, 'update'])->name('password.update');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/plan-request', [PlanRequestController::class, 'store'])->name('plan-request.store');
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// ── Admin routes ──────────────────────────────────────────
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
        Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customers.show');
        Route::patch('/customers/{user}/toggle', [CustomerController::class, 'toggle'])->name('customers.toggle');

        // Payment Info
        Route::get('/payment-info', [PaymentInfoController::class, 'index'])->name('payment-info');
        Route::post('/payment-info/deposit', [PaymentInfoController::class, 'saveDeposit'])->name('payment-info.deposit');
        Route::post('/payment-info/withdraw', [PaymentInfoController::class, 'saveWithdraw'])->name('payment-info.withdraw');
        Route::delete('/payment-info/deposit/{userId}', [PaymentInfoController::class, 'removeDepositOverride'])->name('payment-info.deposit.remove');
        Route::delete('/payment-info/withdraw/{userId}', [PaymentInfoController::class, 'removeWithdrawOverride'])->name('payment-info.withdraw.remove');

        // Investments & Transactions (merged)
        Route::get('/plans', [InvestmentPlanController::class, 'index'])->name('plans');
        Route::get('/plans/check-cycle', [InvestmentPlanController::class, 'checkCycle'])->name('plans.check');
        Route::post('/plans/withdraw', [InvestmentPlanController::class, 'storeWithdraw'])->name('plans.withdraw');
        Route::post('/plans/{plan}/complete', [InvestmentPlanController::class, 'complete'])->name('plans.complete');
        Route::post('/plans', [InvestmentPlanController::class, 'store'])->name('plans.store');
        Route::patch('/plans/txn/{transaction}', [InvestmentPlanController::class, 'updateTxn'])->name('plans.txn.update');
        Route::delete('/plans/txn/{transaction}', [InvestmentPlanController::class, 'destroyTxn'])->name('plans.txn.destroy');
        Route::patch('/plans/{plan}', [InvestmentPlanController::class, 'update'])->name('plans.update');
        Route::delete('/plans/{plan}', [InvestmentPlanController::class, 'destroy'])->name('plans.destroy');

        // Plan Requests
        Route::get('/plan-requests', [PlanRequestAdminController::class, 'index'])->name('plan-requests');
        Route::post('/plan-requests/{planRequest}/approve', [PlanRequestAdminController::class, 'approve'])->name('plan-requests.approve');
        Route::post('/plan-requests/{planRequest}/reject', [PlanRequestAdminController::class, 'reject'])->name('plan-requests.reject');

        // Admin Change Password
        Route::get('/password/change', [AdminPasswordController::class, 'create'])->name('password.change');
        Route::post('/password/change', [AdminPasswordController::class, 'update'])->name('password.update');
    });

require __DIR__.'/auth.php';
