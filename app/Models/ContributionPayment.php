<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContributionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contribution_id',
        'user_id',
        'amount',
        'paid_at'
    ];

    // Payment belongs to Monthly Contribution
    public function contribution()
    {
        return $this->belongsTo(MonthlyContribution::class, 'contribution_id');
    }

    // Payment belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'paid_at' => 'datetime',
        'amount'  => 'decimal:2'
    ];
}
