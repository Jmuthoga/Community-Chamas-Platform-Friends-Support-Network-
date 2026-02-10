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

    protected $casts = [
    'paid_at' => 'datetime'
    ];

}
