<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $fillable = [
        'user_id',
        'contribution_id',
        'amount',
        'date_applied'
    ];

    public function contribution()
    {
        return $this->belongsTo(MonthlyContribution::class, 'contribution_id');
    }
}
