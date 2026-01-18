<?php

namespace App\Services;

use App\Models\Loan;
use Illuminate\Validation\ValidationException;

class LoanService
{
    public function approve(Loan $loan, int $approvedById): Loan
    {
        if ($loan->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Loan must be pending to approve.'
            ]);
        }

        $loan->update([
            'status' => 'approved',
            'approved_by' => $approvedById,
        ]);

        return $loan;
    }

    public function borrow(Loan $loan): Loan
    {
        if ($loan->status !== 'approved') {
            throw ValidationException::withMessages([
                'status' => 'Loan must be approved to borrow.'
            ]);
        }

        $loan->update([
            'status' => 'borrowed',
            'loan_date' => now(),
            'due_date' => now()->addDays(config('library.loan_duration', 14)),
        ]);

        return $loan;
    }

    public function return(Loan $loan): Loan
    {
        if (!in_array($loan->status, ['borrowed', 'overdue'])) {
            throw ValidationException::withMessages([
                'status' => 'Loan must be borrowed or overdue to return.'
            ]);
        }

        $loan->update([
            'status' => 'returned',
            'return_date' => now(),
            'fine_amount' => $this->calculateFine($loan),
        ]);

        return $loan;
    }

    protected function calculateFine(Loan $loan): int
    {
        if (!$loan->return_date || !$loan->due_date) {
            return 0;
        }

        if ($loan->return_date <= $loan->due_date) {
            return 0;
        }

        $daysLate = $loan->due_date->diffInDays($loan->return_date);

        $finePerDay = setting('fine_per_day', 1000);

        return $daysLate * $finePerDay;
    }

}