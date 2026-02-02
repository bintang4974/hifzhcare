<?php

use App\Http\Controllers\{
    DashboardController,
    ClassController,
    HafalanController,
    ProfileController,
    SantriController
};
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', 'tenant'])->group(function () {

    // Dashboard
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
        Route::resource('santri', SantriController::class);
        Route::get('santri/export', [SantriController::class, 'export'])->name('santri.export');
        Route::get('santri/stats', [SantriController::class, 'stats'])->name('santri.stats');
        Route::post('santri/{santri}/activate', [SantriController::class, 'activate'])
            ->name('santri.activate');
    });

    // Classes
    // Allow authenticated users to view a class detail (ustadz can view their classes)
    Route::get('classes/{class}', [ClassController::class, 'show'])->name('classes.show');

    Route::middleware(['can:manage_classes'])->group(function () {
        // Resource routes for classes except 'show' which is accessible to non-admins
        Route::resource('classes', ClassController::class)->except(['show']);
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
});

// Route::middleware(['auth', 'verified'])->group(function () {

//     // Dashboard
//     // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     // Hafalan Routes
//     Route::prefix('hafalan')->name('hafalan.')->group(function () {
//         // Resource routes (index, create, store, show, edit, update, destroy)
//         Route::resource('', HafalanController::class)->parameters(['' => 'hafalan']);

//         // Additional routes
//         Route::post('/{hafalan}/verify', [HafalanController::class, 'verify'])->name('verify');
//         Route::post('/{hafalan}/reject', [HafalanController::class, 'reject'])->name('reject');
//         Route::get('/progress/{user?}', [HafalanController::class, 'progress'])->name('progress');
//     });

//     // Users - Santri
//     Route::middleware(['can:manage_users', 'check.quota:santri'])->group(function () {
//         Route::resource('users/santri', SantriController::class);
//         Route::post('users/santri/{santri}/activate', [SantriController::class, 'activate'])
//              ->name('users.santri.activate');
//     });

//     // User Management Routes
//     Route::middleware(['can:manage_users'])->group(function () {
//         Route::prefix('users')->name('users.')->group(function () {
//             // Will be implemented in next step
//             // Route::resource('santri', SantriController::class);
//             // Route::resource('ustadz', UstadzController::class);
//             // Route::resource('wali', WaliController::class);
//         });
//     });

//     // Class Management Routes
//     Route::middleware(['can:manage_classes'])->group(function () {
//         Route::prefix('classes')->name('classes.')->group(function () {
//             // Will be implemented in next step
//             // Route::resource('', ClassController::class);
//             // Route::post('/{class}/assign-ustadz', [ClassController::class, 'assignUstadz']);
//             // Route::post('/{class}/enroll-santri', [ClassController::class, 'enrollSantri']);
//         });
//     });

//     // Certificate Routes
//     Route::prefix('certificates')->name('certificates.')->group(function () {
//         // Will be implemented in next step
//         // Route::get('/', [CertificateController::class, 'index'])->name('index');
//         // Route::post('/request', [CertificateController::class, 'request'])->name('request');
//         // Route::post('/{certificate}/approve', [CertificateController::class, 'approve'])->name('approve');
//     });

//     // Reports Routes
//     Route::middleware(['can:view_reports'])->group(function () {
//         Route::prefix('reports')->name('reports.')->group(function () {
//             // Will be implemented in next step
//             // Route::get('/progress', [ReportController::class, 'progress'])->name('progress');
//             // Route::get('/statistics', [ReportController::class, 'statistics'])->name('statistics');
//         });
//     });

//     // Settings Routes
//     Route::prefix('settings')->name('settings.')->group(function () {
//         // Route::get('/profile', [SettingsController::class, 'profile'])->name('profile');
//         // Route::get('/pesantren', [SettingsController::class, 'pesantren'])->name('pesantren');
//     });
// });

require __DIR__ . '/auth.php';
