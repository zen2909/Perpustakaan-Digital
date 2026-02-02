<?php

namespace App\Http\Controllers\admin;

use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoanApprovalController extends Controller
{
    public function __construct(protected LoanService $loanservice)
    {
    }

    public function approve(Loan $loan): RedirectResponse
    {
        $this->loanservice->approve($loan, auth()->id());
        $this->loanservice->sendApprovalMail($loan);

        return back()->with('success', 'Peminjaman Telah di Konfirmasi.');
    }
    public function borrow(Loan $loan): RedirectResponse
    {
        $this->loanservice->borrow($loan);

        return back()->with('success', 'Buku Telah Dipinjam.');
    }
    public function returnBook(Loan $loan): RedirectResponse
    {
        $this->loanservice->returnBook($loan);

        return back()->with('success', 'Buku Telah Dikembalikan.');
    }

    public function markAsOverdue(Loan $loan): RedirectResponse
    {
        foreach ($loan as $loan => $index) {
            $this->loanservice->markAsOverdue($loan);
        }
    }

}