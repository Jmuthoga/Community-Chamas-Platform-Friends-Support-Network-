<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContributionSetting extends Model
{
    protected $fillable = [
        'monthly_amount',
        'penalty_per_day',
        'due_day',
        'grace_day'
    ];
}
