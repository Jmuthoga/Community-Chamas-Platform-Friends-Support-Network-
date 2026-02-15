<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MonthlyContribution;
use App\Models\ContributionPayment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = now()->month;
        $year  = now()->year;

        $monthlyQuery = MonthlyContribution::where('month', $month)
                                           ->where('year', $year);

        $totalMembers = User::count();
        $monthlyCollected = $monthlyQuery->sum('paid_amount');
        $monthlyPenalties = $monthlyQuery->sum('penalty');
        $membersContributed = $monthlyQuery->where('paid_amount', '>', 0)->count();
        $membersRemaining = max($totalMembers - $membersContributed, 0);
        $monthlyRemainingBalance = $monthlyQuery->sum(DB::raw('total_amount - paid_amount'));

        $allTimeCollected = MonthlyContribution::sum('paid_amount');
        $allTimePenalties = MonthlyContribution::sum('penalty');
        $totalPayments = ContributionPayment::where('status','completed')->count();
        $fullyPaidMonths = MonthlyContribution::whereColumn('paid_amount','>=','total_amount')->count();

        $monthlyTarget = 100000;
        $allTimeTarget = 5000000;

        // Prepare data for monthly contributions chart (all 12 months)
        $months = [];
        $monthlyCollectedPerMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = date('F', mktime(0, 0, 0, $m, 1));
            $monthlyCollectedPerMonth[] = MonthlyContribution::where('month', $m)
                                                ->where('year', $year)
                                                ->sum('paid_amount');
        }

        return view('backend.index', compact(
            'monthlyCollected',
            'monthlyPenalties',
            'membersContributed',
            'membersRemaining',
            'monthlyRemainingBalance',
            'allTimeCollected',
            'allTimePenalties',
            'totalPayments',
            'fullyPaidMonths',
            'monthlyTarget',
            'allTimeTarget',
            'totalMembers',
            'months',
            'monthlyCollectedPerMonth'
        ));
    }
    public function profile()
    {
        $user = auth()->user();
        return view('backend.profile.index', compact('user'));
    }
}
