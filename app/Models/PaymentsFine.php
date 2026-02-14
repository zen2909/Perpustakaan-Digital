<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentsFine extends Model
{
    protected $table = 'payments_fine';

    protected $fillable = [
        'loan_id',
        'user_id',
        'external_id',
        'xendit_invoice_id',
        'amount',
        'status',
        'paid_at',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'paid_at' => 'datetime',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}