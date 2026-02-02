<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\AuthorController;
use App\Http\Controllers\admin\BookController;
use App\Http\Controllers\admin\LoanController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\member\CatalogController;
use App\Http\Controllers\admin\LoanApprovalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('authors', AuthorController::class)->except(['show']);
        Route::delete('/books/{book}', [BookController::class, 'destroy'])
            ->name('books.destroy');
    });

    Route::middleware('role:admin,librarian')->group(function () {
        Route::resource('books', BookController::class)->except(['destroy']);
    });

    Route::middleware(['auth', 'role:admin,librarian'])
        ->prefix('admin/loans')
        ->name('admin.loans.')
        ->group(function () {

            Route::post('/scan-qr', [LoanController::class, 'scanQr'])
                ->name('scan-qr');

            Route::get('/', [LoanController::class, 'loan'])
                ->name('index');

            Route::post('/{loan}/approve', [LoanApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{loan}/borrow', [LoanApprovalController::class, 'borrow'])
                ->name('borrow');
            Route::post('/{loan}/return', [LoanApprovalController::class, 'return'])
                ->name('return');
            Route::delete('/{loan}/destroy', [LoanController::class, 'destroy'])
                ->name('destroy');
        });

    Route::middleware('role:member')->group(function () {
        Route::get('/catalogs', [CatalogController::class, 'index'])->name('members.catalog');
        Route::post('/catalogs', [LoanController::class, 'store'])->name('members.store');
        Route::get('/my-loans', [LoanController::class, 'index'])->name('members.loan');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';