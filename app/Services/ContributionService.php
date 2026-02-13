<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Penalty;
use App\Models\MonthlyContribution;
use App\Models\ContributionSetting;
use App\Models\ContributionPayment;

class ContributionService
{

    // ====================== Generate Monthly Contributions ======================
    public function generateMonthlyContributions()
    {
        $settings = ContributionSetting::first();

        // Safety fallback if settings missing
        $monthlyAmount = $settings->monthly_amount ?? 500;

        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        $users = User::where('membership_status', 'active')->get();

        foreach ($users as $user) {

            MonthlyContribution::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'month'   => $month,
                    'year'    => $year
                ],
                [
                    'amount_due'   => $monthlyAmount,
                    'total_amount' => $monthlyAmount
                ]
            );
        }
    }

    // ====================== GApply Penalties ======================
    public function applyPenalties()
    {
        $settings = ContributionSetting::first();

        // Safety fallback values
        $penaltyPerDay = $settings->penalty_per_day ?? 100;
        $graceDay      = $settings->grace_day ?? 16;

        $today = Carbon::today();

        // Stop if still within grace period
        if ($today->day <= $graceDay) {
            return;
        }

        $month = $today->month;
        $year  = $today->year;

        $daysLate = $today->day - $graceDay;
        $penaltyAmount = $daysLate * $penaltyPerDay;

        $contributions = MonthlyContribution::where('month', $month)
            ->where('year', $year)
            ->where('status', 'unpaid')
            ->get();

        foreach ($contributions as $contribution) {

            // Prevent duplicate penalty calculation
            if ($contribution->penalty >= $penaltyAmount) {
                continue;
            }

            $difference = $penaltyAmount - $contribution->penalty;

            // Update contribution totals
            $contribution->penalty = $penaltyAmount;
            $contribution->total_amount = $contribution->amount_due + $penaltyAmount;
            $contribution->save();

            // Save penalty audit trail
            Penalty::create([
                'user_id' => $contribution->user_id,
                'contribution_id' => $contribution->id,
                'amount' => $difference,
                'date_applied' => $today
            ]);
        }
    }

    public function makeContribution(User $user, $amount)
    {
        $today = Carbon::now();

        $settings = ContributionSetting::first();
        $monthlyAmount = $settings->monthly_amount ?? 500;

        $contribution = MonthlyContribution::firstOrCreate(
            [
                'user_id' => $user->id,
                'month' => $today->month,
                'year' => $today->year
            ],
            [
                'amount_due' => $monthlyAmount,
                'total_amount' => $monthlyAmount,
                'paid_amount' => 0,
                'penalty' => 0,
                'status' => 'unpaid'
            ]
        );

        // 🔥 Sync amount with settings
        if ($contribution->paid_amount == 0) {
            $contribution->amount_due = $monthlyAmount;
        }

        $balance = $contribution->total_amount - $contribution->paid_amount;

        if ($amount > $balance) {
            throw new \Exception("Payment exceeds balance.");
        }

        ContributionPayment::create([
            'contribution_id' => $contribution->id,
            'user_id' => $user->id,
            'amount' => $amount,
            'paid_at' => now()
        ]);

        $contribution->paid_amount += $amount;

        if ($contribution->paid_amount >= $contribution->total_amount) {
            $contribution->status = 'paid';
            $contribution->paid_at = now();
        }

        $contribution->save();

        // 🔥 REQUIRED
        $contribution->refreshTotals();
    }
}
