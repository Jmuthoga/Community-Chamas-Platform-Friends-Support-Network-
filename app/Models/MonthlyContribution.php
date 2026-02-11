<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyContribution extends Model
{
    protected $fillable = [
        'user_id',
        'month',
        'year',
        'amount_due',
        'penalty',
        'total_amount',
        'paid_amount',
        'status',
        'paid_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class, 'contribution_id');
    }

    public function payments()
    {
        return $this->hasMany(ContributionPayment::class, 'contribution_id');
    }

    public function getBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    protected $casts = [
    'paid_at' => 'datetime'
    ];

}
