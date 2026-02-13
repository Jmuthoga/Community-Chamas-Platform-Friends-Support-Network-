<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyContribution extends Model
{
    use HasFactory;

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

    public function calculatePenalty()
    {
        $settings = ContributionSetting::first();

        if (!$settings) {
            return 0;
        }

        $dueDate = \Carbon\Carbon::create($this->year, $this->month, $settings->due_day);
        $graceDate = \Carbon\Carbon::create($this->year, $this->month, $settings->grace_day);

        if (now()->lte($graceDate) || $this->status === 'paid') {
            return 0;
        }

        if (now()->lte($graceDate)) {
            return 0;
        }

        $lateDays = $graceDate->diffInDays(now());

        return $lateDays * $settings->penalty_per_day;
    }

    public function refreshTotals()
    {
        $settings = ContributionSetting::first();

        if (!$settings) return;

        $penalty = $this->calculatePenalty();

        $this->update([
            'penalty' => $penalty,
            'total_amount' => $settings->monthly_amount + $penalty
        ]);
    }

    protected $casts = [
    'paid_at' => 'datetime'
    ];

}
