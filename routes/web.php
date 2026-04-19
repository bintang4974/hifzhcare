<?php

use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HafalanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\StakeholderDashboardController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\UstadzController;
use App\Http\Controllers\WaliController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', 'tenant'])->group(function () {
    // Debug: Check user permissions
    Route::get('/debug/permissions', function () {
        return view('debug.permissions');
    })->name('debug.permissions');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Hafalan (already added)
    // Hafalan Routes
    Route::prefix('hafalan')->name('hafalan.')->group(function () {
        // Resource routes (index, create, store, show, edit, update, destroy)
        Route::resource('', HafalanController::class)->parameters(['' => 'hafalan']);

        // Additional routes
        Route::post('/{hafalan}/verify', [HafalanController::class, 'verify'])->name('verify');
        Route::post('/{hafalan}/reject', [HafalanController::class, 'reject'])->name('reject');
        Route::get('/progress/{user?}', [HafalanController::class, 'progress'])->name('progress');
    });
    // Route::resource('hafalan', HafalanController::class);

    // Users - Santri
    Route::middleware(['can:manage_users', 'check.quota:santri'])->prefix('users')->name('users.')->group(function () {
        // IMPORTANT: Static routes MUST come before resource routes
        Route::get('santri/export', [SantriController::class, 'export'])->name('santri.export');
        Route::get('santri/stats', [SantriController::class, 'stats'])->name('santri.stats');
        // Resource routes MUST come after static routes
        Route::resource('santri', SantriController::class);
        Route::post('santri/{santri}/activate', [SantriController::class, 'activate'])
            ->name('santri.activate');
    });

    // ====================================
    // CERTIFICATE ROUTES - ADDED
    // ====================================
    Route::prefix('certificates')->name('certificates.')->group(function () {
        // List & Stats
        Route::get('/', [CertificateController::class, 'index'])->name('index');

        // Manual Generate (Admin only)
        Route::middleware(['can:manage_users'])->group(function () {
            Route::get('/generate', [CertificateController::class, 'generateForm'])->name('generate');
            Route::post('/generate', [CertificateController::class, 'storeManual'])->name('storeManual');
        });
    });

    // Classes
    Route::middleware(['can:manage_classes'])->group(function () {
        // IMPORTANT: Create route must be BEFORE {class} parameter route to avoid conflict
        Route::get('classes/create', [ClassController::class, 'create'])->name('classes.create');
        Route::get('classes/{class}/edit', [ClassController::class, 'edit'])->name('classes.edit');

        // Resource routes for classes except 'show', 'create', 'edit'
        Route::resource('classes', ClassController::class)->except(['show', 'create', 'edit']);

        Route::get('classes/{class}/members', [ClassController::class, 'members'])
            ->name('classes.members');
        Route::post('classes/{class}/assign-ustadz', [ClassController::class, 'assignUstadz'])
            ->name('classes.assign-ustadz');
        Route::delete('classes/{class}/ustadz/{ustadz}', [ClassController::class, 'removeUstadz'])
            ->name('classes.remove-ustadz');
        Route::post('classes/{class}/enroll-santri', [ClassController::class, 'enrollSantri'])
            ->name('classes.enroll-santri');
        Route::delete('classes/{class}/santri/{santri}', [ClassController::class, 'removeSantri'])
            ->name('classes.remove-santri');
        Route::post('classes/{class}/graduate-santri/{santri}', [ClassController::class, 'graduateSantri'])
            ->name('classes.graduate-santri');
    });

    // Allow authenticated users to view a class detail (ustadz can view their classes)
    Route::get('classes/{class}', [ClassController::class, 'show'])->name('classes.show');

    // Ustadz Routes
    Route::prefix('users/ustadz')->name('users.ustadz.')->group(function () {
        Route::get('/', [UstadzController::class, 'index'])->name('index');
        // IMPORTANT: Static routes MUST come before parameter routes
        Route::get('/stats', [UstadzController::class, 'stats'])->name('stats');
        Route::get('/create', [UstadzController::class, 'create'])->name('create');
        // Parameter routes MUST come last
        Route::post('/', [UstadzController::class, 'store'])->name('store');
        Route::get('/{ustadz}', [UstadzController::class, 'show'])->name('show');
        Route::get('/{ustadz}/edit', [UstadzController::class, 'edit'])->name('edit');
        Route::put('/{ustadz}', [UstadzController::class, 'update'])->name('update');
        Route::delete('/{ustadz}', [UstadzController::class, 'destroy'])->name('destroy');
        Route::post('/{ustadz}/activate', [UstadzController::class, 'activate'])->name('activate');
        Route::patch('/{ustadz}/toggle-status', [UstadzController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Wali Routes
    Route::prefix('users/wali')->name('users.wali.')->group(function () {
        Route::get('/', [WaliController::class, 'index'])->name('index');
        Route::get('/stats', [WaliController::class, 'stats'])->name('stats');
        Route::get('/create', [WaliController::class, 'create'])->name('create');
        Route::post('/', [WaliController::class, 'store'])->name('store');
        Route::get('/{wali}', [WaliController::class, 'show'])->name('show');
        Route::get('/{wali}/edit', [WaliController::class, 'edit'])->name('edit');
        Route::put('/{wali}', [WaliController::class, 'update'])->name('update');
        Route::delete('/{wali}', [WaliController::class, 'destroy'])->name('destroy');
    });

    // ====================================
    // REPORTS ROUTES
    // ====================================
    Route::middleware(['can:manage_users'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');

        // Santri Reports
        Route::post('/santri-data', [ReportController::class, 'santriData'])->name('santri-data');
        Route::post('/santri-progress', [ReportController::class, 'santriProgress'])->name('santri-progress');
        Route::post('/santri-ranking', [ReportController::class, 'santriRanking'])->name('santri-ranking');

        // Class Reports
        Route::post('/class-overview', [ReportController::class, 'classOverview'])->name('class-overview');
        Route::post('/class-performance', [ReportController::class, 'classPerformance'])->name('class-performance');

        // Hafalan Reports
        Route::post('/hafalan-summary', [ReportController::class, 'hafalanSummary'])->name('hafalan-summary');
        Route::post('/hafalan-juz', [ReportController::class, 'hafalanJuz'])->name('hafalan-juz');

        // Certificate Reports
        Route::post('/certificate-summary', [ReportController::class, 'certificateSummary'])->name('certificate-summary');
    });
});

// Super Admin Routes
Route::middleware(['auth', 'role:Super Admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    // Pesantren CRUD
    Route::get('/pesantrens', [SuperAdminDashboardController::class, 'pesantrens'])->name('pesantrens');
    Route::get('/pesantrens/create', [SuperAdminDashboardController::class, 'createPesantren'])->name('pesantrens.create');
    Route::post('/pesantrens', [SuperAdminDashboardController::class, 'storePesantren'])->name('pesantrens.store');
    Route::get('/pesantrens/{id}', [SuperAdminDashboardController::class, 'showPesantren'])->name('pesantrens.show');
    Route::get('/pesantrens/{id}/edit', [SuperAdminDashboardController::class, 'editPesantren'])->name('pesantrens.edit');
    Route::put('/pesantrens/{id}', [SuperAdminDashboardController::class, 'updatePesantren'])->name('pesantrens.update');
    Route::delete('/pesantrens/{id}', [SuperAdminDashboardController::class, 'destroyPesantren'])->name('pesantrens.destroy');

    // Pesantren Actions
    Route::post('/pesantrens/{id}/toggle', [SuperAdminDashboardController::class, 'togglePesantrenStatus'])->name('pesantrens.toggle');

    // Pesantren Settings
    Route::get('/pesantrens/{id}/settings', [SuperAdminDashboardController::class, 'showSettings'])->name('pesantrens.settings');
    Route::put('/pesantrens/{id}/settings', [SuperAdminDashboardController::class, 'updateSettings'])->name('pesantrens.updateSettings');

    // Statistics
    Route::get('/statistics', [SuperAdminDashboardController::class, 'statistics'])->name('statistics');

    // Admin Management CRUD
    Route::get('/admins', [AdminManagementController::class, 'index'])->name('admins.index');
    Route::get('/admins/create', [AdminManagementController::class, 'create'])->name('admins.create');
    Route::post('/admins', [AdminManagementController::class, 'store'])->name('admins.store');
    Route::get('/admins/{id}/edit', [AdminManagementController::class, 'edit'])->name('admins.edit');
    Route::put('/admins/{id}', [AdminManagementController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{id}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');

    // Admin Actions
    Route::post('/admins/{id}/assign', [AdminManagementController::class, 'assign'])->name('admins.assign');
    Route::post('/admins/{id}/unassign', [AdminManagementController::class, 'unassign'])->name('admins.unassign');
    Route::post('/admins/{id}/activate', [AdminManagementController::class, 'activate'])->name('admins.activate');
    Route::post('/admins/{id}/toggle', [AdminManagementController::class, 'toggleStatus'])->name('admins.toggle');
    Route::post('/admins/{id}/reset-password', [AdminManagementController::class, 'resetPassword'])->name('admins.resetPassword');
    Route::post('/admins/{id}/send-credentials', [AdminManagementController::class, 'sendCredentials'])->name('admins.sendCredentials');
});

Route::middleware(['auth', 'tenant', 'role:Stakeholder'])->prefix('stakeholder')->name('stakeholder.')->group(function () {
    // Main dashboard
    Route::get('/dashboard', [StakeholderDashboardController::class, 'index'])->name('dashboard');

    // Export reports
    Route::post('/export-report', [StakeholderDashboardController::class, 'exportReport'])->name('export');
});

require __DIR__ . '/auth.php';
