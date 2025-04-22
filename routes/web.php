<?php

use App\Http\Controllers\OpenSessionController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MemberCategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\Default\FileController;
use App\Http\Controllers\Default\GeneralController;
use App\Http\Controllers\Default\PermissionController;
use App\Http\Controllers\Default\ProfileController;
use App\Http\Controllers\Default\RoleController;
use App\Http\Controllers\Default\SettingController;
use App\Http\Controllers\Default\UserController;
use Illuminate\Support\Facades\Route;

// define module as main route
// Route::get('/', [App\Module\Shortlink\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', function () {
    return redirect('/login');
});

Route::get('files/{file}', [FileController::class, 'show'])->name('file.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [GeneralController::class, 'index'])->name('dashboard');
    Route::get('/maintance', [GeneralController::class, 'maintance'])->name('maintance');

    // User
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::post('/users', [UserController::class, 'store'])->name('user.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    // Permission
    Route::delete('_permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::put('_permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::post('_permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('_permissions', [PermissionController::class, 'index'])->name('permissions.index');

    // Role
    Route::resource('/roles', RoleController::class);

    // Setting
    Route::get('/settings', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('setting.update');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // #Admin
    Route::delete('open-sessions/{openSession}', [OpenSessionController::class, 'destroy'])->name('open-sessions.destroy');
    Route::post('open-sessions', [OpenSessionController::class, 'store'])->name('open-sessions.store');
    Route::get('open-sessions', [OpenSessionController::class, 'index'])->name('open-sessions.index');

    Route::get('memberships', [MembershipController::class, 'index'])->name('memberships.index');

    Route::get('transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
    Route::resource('transactions', TransactionController::class);

    Route::delete('member-categories/{memberCategory}', [MemberCategoryController::class, 'destroy'])->name('member-categories.destroy');
    Route::put('member-categories/{memberCategory}', [MemberCategoryController::class, 'update'])->name('member-categories.update');
    Route::post('member-categories', [MemberCategoryController::class, 'store'])->name('member-categories.store');
    Route::get('member-categories', [MemberCategoryController::class, 'index'])->name('member-categories.index');

    Route::get('members/{member}/print', [MemberController::class, 'print'])->name('members.print');
    Route::get('members/{member}/show', [MemberController::class, 'show'])->name('members.show');
    Route::delete('members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::put('members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::post('members', [MemberController::class, 'store'])->name('members.store');
    Route::get('members', [MemberController::class, 'index'])->name('members.index');

    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::put('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::delete('accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    Route::put('accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
    Route::post('accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::resource('bundles', BundleController::class);
    Route::delete('subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
    Route::put('subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
    // 
});

// #Guest


// Route::get('/{link:code}', [App\Module\Shortlink\Controllers\HomeController::class, 'redirect'])->name('redirect');