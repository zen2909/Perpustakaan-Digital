<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Setting;
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

        $qrContent = $loan->qr_token;

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

        if ($loan->qr_path) {
            Storage::disk('public')->delete($loan->qr_path);
        }

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );

        $token_return = Str::uuid();

        $writer = new Writer($renderer);

        $qrContent = $token_return;

        $qrImage = $writer->writeString($qrContent);

        $qrPath = 'qr/loans/loan-' . $token_return . '.png';

        Storage::disk('public')->put($qrPath, $qrImage);

        $loan->update([
            'status' => 'borrowed',
            'loan_date' => now(),
            'due_date' => now()->addMinutes(Setting::getValue('loan_duration_testing', 2)),
            'qr_path' => $qrPath,
            'token_return' => $token_return,
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

        $fine_status = null;

        if ($loan->status === 'overdue') {
            $fine_status = 'unpaid';
        }

        $returnDate = now();

        $fine = $this->calculateFine($loan, $returnDate);

        $loan->update([
            'status' => 'returned',
            'return_date' => $returnDate,
            'fine_amount' => $fine,
            'fine_status' => $fine_status,
        ]);

        return $loan;
    }

    protected function calculateFine(Loan $loan, $returnDate): int
    {
        if (!$loan->due_date) {
            return 0;
        }

        if ($returnDate->lessThanOrEqualTo($loan->due_date)) {
            return 0;
        }

        $minutesLate = $loan->due_date->diffInMinutes($loan->return_date);

        $finePerMinute = Setting::getValue('fine_per_minute', 1000);

        return $minutesLate * $finePerMinute;
    }

    public function markAsOverdue(Loan $loan): Loan
    {
        if (
            $loan->status !== 'borrowed' ||
            $loan->due_date === null ||
            $loan->due_date->isFuture()
        ) {
            return $loan;
        }

        $loan->update([
            'status' => 'overdue',
        ]);

        return $loan;
    }

}