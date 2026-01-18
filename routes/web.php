<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
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

    Route::middleware(['role:admin,librarian'])
        ->prefix('admin/loans')
        ->name('admin.loans.')
        ->group(function () {

            Route::post('{loan}/approve', [LoanApprovalController::class, 'approve'])
                ->name('approve');

            Route::post('{loan}/borrow', [LoanApprovalController::class, 'borrow'])
                ->name('borrow');

            Route::post('{loan}/return', [LoanApprovalController::class, 'return'])
                ->name('return');
        });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';