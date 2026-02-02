<?php

namespace App\Services;

use App\Models\Loan;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Mail\LoanApprovedMail;
use Illuminate\Support\Facades\Mail;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Book;

class LoanService
{
    public function approve(Loan $loan, int $approvedById): Loan
    {

        if ($loan->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Loan must be pending to approve.'
            ]);
        }

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );

        $writer = new Writer($renderer);

        $qrContent = 'loan:' . $loan->qr_token;

        $qrImage = $writer->writeString($qrContent);

        $qrPath = 'qr/loans/loan-' . $loan->qr_token . '.png';

        Storage::disk('public')->put($qrPath, $qrImage);

        $loan->update([
            'status' => 'approved',
            'approved_by' => $approvedById,
            'qr_path' => $qrPath,
        ]);

        return $loan;
    }

    public function sendApprovalMail(Loan $loan): void
    {
        Mail::to($loan->user->email)
            ->send(new LoanApprovedMail($loan));
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
            'due_date' => now()->addDays(config('library.loan_duration', default: 14)),
        ]);

        return $loan;
    }

    public function returnBook(Loan $loan): Loan
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

    public function markAsOverdue(Loan $loan): Loan
    {
        if ($loan->status != 'borrowed' || $loan->due_date == null || $loan->due_date >= isFuture()) {
            return null;
        }

        $loan->update([
            'status' => 'overdue',
        ]);
        return $loan;
    }

}