<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoanApprovalController extends Controller
{
    public function __construct(protected LoanService $loanservice)
    {
    }

    public function approved(Loan $loan): RedirectResponse
    {
        $this->loanservice->approve($loan, auth()->id());

        return back()->with('success', 'Peminjaman Telah di Konfirmasi.');
    }
    public function borrow(Loan $loan): RedirectResponse
    {
        $this->loanservice->borrow($loan);

        return back()->with('success', 'Buku Telah Dipinjam.');
    }
    public function return(Loan $loan): RedirectResponse
    {
        $this->loanservice->approve($loan);

        return back()->with('success', 'Buku Telah Dikembalikan.');
    }
}