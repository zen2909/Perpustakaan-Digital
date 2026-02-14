<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\AuthorController;
use App\Http\Controllers\admin\BookController;
use App\Http\Controllers\admin\LoanController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\member\CatalogController;
use App\Http\Controllers\admin\LoanApprovalController;
use App\Http\Controllers\PaymentFineController;
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

            Route::get('/', [LoanController::class, 'loan'])
                ->name('index');
            Route::post('/{loan}/approve', [LoanController::class, 'approve'])
                ->name('approve');
            Route::delete('/{loan}/destroy', [LoanController::class, 'destroy'])
                ->name('destroy');
            Route::post('/scan-loan', [LoanController::class, 'scanLoan'])
                ->name('scan-loan');
            Route::post('/scan-return', [LoanController::class, 'scanReturn'])
                ->name('scan-return');
        });

    Route::middleware('role:member')->group(function () {
        Route::get('/catalogs', [CatalogController::class, 'index'])->name('members.catalog');
        Route::post('/catalogs', [LoanController::class, 'store'])->name('members.store');
        Route::get('/my-loans', [LoanController::class, 'index'])->name('members.loan');
        Route::post('/loans/{loan}/pay-fine', [PaymentFineController::class, 'create'])->name('loans.payFine');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





require __DIR__ . '/auth.php';