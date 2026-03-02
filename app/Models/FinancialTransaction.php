<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'type',
        'amount',
        'description',
        'payment_method',
        'transaction_date',
        'source_id',
        'source_type',
    ];

    protected $casts = [
        'transaction_date' => 'datetime', // <-- Add this
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function source()
    {
        return $this->morphTo();
    }
}
