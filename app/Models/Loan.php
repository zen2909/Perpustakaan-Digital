<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Book;
use App\Models\User;

class Loan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'approved_by',
        'fine_amount',
        'qr_path',
        'qr_token',
        'token_return',
        'overdue_notified_at',
        'payment_token',
        'payment_method',
        'fine_status'
    ];

    protected $casts = [
        'loan_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime',
        'overdue_notified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function payments()
    {
        return $this->hasMany(PaymentsFine::class);
    }


}