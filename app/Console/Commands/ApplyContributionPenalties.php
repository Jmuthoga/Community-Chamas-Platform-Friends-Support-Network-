<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MonthlyContribution;
use App\Models\ContributionSetting;
use App\Models\Penalty;
use Carbon\Carbon;

class ApplyContributionPenalties extends Command
{
    protected $signature = 'contributions:apply-penalties';
    protected $description = 'Automatically apply penalties to unpaid contributions after grace period';

    public function handle()
    {
        $settings = ContributionSetting::first();
        if (!$settings) {
            $this->info('No contribution settings found.');
            return 0;
        }

        $today = Carbon::today();

        // Fetch all unpaid contributions
        $unpaidContributions = MonthlyContribution::where('status', 'unpaid')->get();

        foreach ($unpaidContributions as $contribution) {
            $year = $contribution->year;
            $month = $contribution->month;

            // Calculate grace date
            $graceDate = Carbon::create($year, $month, $settings->grace_day);

            if ($today->gt($graceDate)) {
                // Days overdue
                $daysOverdue = $today->diffInDays($graceDate);

                // Calculate penalty
                $penaltyAmount = $daysOverdue * $settings->penalty_per_day;

                // Update contribution
                $contribution->update([
                    'penalty' => $penaltyAmount,
                    'total_amount' => $contribution->amount_due + $penaltyAmount
                ]);

                // Optionally log or store individual penalty records
                Penalty::updateOrCreate(
                    [
                        'user_id' => $contribution->user_id,
                        'contribution_id' => $contribution->id,
                        'date_applied' => $today,
                    ],
                    [
                        'amount' => $penaltyAmount
                    ]
                );
            }
        }

        $this->info('Penalties applied successfully.');
        return 0;
    }
}
