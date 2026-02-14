<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use App\Models\Loan;
use App\Models\PaymentsFine;

class PaymentFineController extends Controller
{
    public function create(Loan $loan)
    {
        if ($loan->fine_amount <= 0) {
            return back()->with('error', 'Tidak ada denda.');
        }

        Configuration::setXenditKey(config('xendit.secret_key'));

        $externalId = 'loan-fine-' . $loan->id . '-' . time();

        $apiInstance = new InvoiceApi();

        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => $externalId,
            'amount' => (int) $loan->fine_amount,
            'description' => 'Pembayaran denda keterlambatan',
            'payer_email' => $loan->user->email,
            'currency' => 'IDR',
            'payment_methods' => ['QRIS'],
            'success_redirect_url' => route('members.loan'),
        ]);

        $invoice = $apiInstance->createInvoice($createInvoiceRequest);

        PaymentsFine::create([
            'loan_id' => $loan->id,
            'user_id' => $loan->user_id,
            'external_id' => $externalId,
            'xendit_invoice_id' => $invoice->getId(),
            'amount' => (int) $invoice['paid_amount'],
            'status' => 'pending',
            'payload' => json_encode($invoice),
        ]);

        return redirect($invoice->getInvoiceUrl());
    }

    public function webhook(Request $request)
    {
        try {

            \Log::info('=== WEBHOOK MASUK ===');
            \Log::info($request->all());

            $externalId = $request->external_id;
            $status = $request->status;

            \Log::info("External ID: " . $externalId);
            \Log::info("Status: " . $status);

            $payment = PaymentsFine::where('external_id', $externalId)->first();

            if (!$payment) {
                \Log::error('Payment tidak ditemukan');
                return response()->json(['message' => 'Payment not found'], 200);
            }

            \Log::info('Payment ditemukan ID: ' . $payment->id);

            if ($status === 'PAID') {

                $payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'payload' => json_encode($request->all())
                ]);

                \Log::info('Payment berhasil diupdate');

                Loan::where('id', $payment->loan_id)
                    ->update(['fine_status' => 'paid']);

                \Log::info('Loan berhasil diupdate');
            }

            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {

            \Log::error('WEBHOOK ERROR: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json(['message' => 'ERROR'], 500);
        }
    }


}