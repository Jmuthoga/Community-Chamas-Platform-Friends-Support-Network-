<?php

namespace App\Http\Controllers\Backend\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\ContributionPayment;
use App\Models\MonthlyContribution;
use App\Services\ContributionService;
use App\Models\ContributionSetting;

class MemberContributionPaymentController extends Controller
{

    // ================= MEMBER PAYMENT HISTORY =================
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {

            $payments = ContributionPayment::with('contribution')
                ->where('user_id', $user->id)
                ->latest()
                ->get();

            return DataTables::of($payments)
                ->addIndexColumn()
                ->addColumn('month_year', function ($row) {
                return optional($row->contribution)->month . ' / ' . optional($row->contribution)->year;
            })
                ->addColumn('amount', fn($row) => number_format($row->amount, 2))
                ->addColumn('contribution_id', fn($row) => $row->contribution_id)
                ->addColumn('paid_at', function ($row) {
                    return $row->paid_at
                        ? $row->paid_at->format('d M Y H:i:s')
                        : '-';
                })
                ->addColumn('status', function ($row) {
                return optional($row->contribution)->status === 'paid'
                    ? '<span class="badge badge-success">Completed</span>'
                        : '<span class="badge badge-warning">Installment</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('backend.contributions.payments');
    }


    // ================= MAKE CONTRIBUTION PAYMENT =================
    public function create()
    {
        $userId = Auth::id();
        $settings = ContributionSetting::firstOrFail();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get current contribution for this month, or create if it doesn't exist
        $contribution = MonthlyContribution::firstOrCreate(
            [
                'user_id' => $userId,
                'month' => $currentMonth,
                'year' => $currentYear,
            ],
            [
                'amount_due' => $settings->monthly_amount,
                'total_amount' => $settings->monthly_amount,
                'paid_amount' => 0,
                'status' => 'unpaid',
                'penalty' => 0,
            ]
        );

        // If contribution exists but is unpaid, update to match latest settings
        if ($contribution->status === 'unpaid') {
            $contribution->update([
                'amount_due' => $settings->monthly_amount,
            ]);
        }

        // Recalculate totals including penalties
        if (method_exists($contribution, 'refreshTotals')) {
            $contribution->refreshTotals();
        }

        // Balance now includes penalties
        $contribution->balance = max(0, $contribution->total_amount - $contribution->paid_amount);

        return view('backend.contributions.create_payment', compact('contribution'));
    }

    public function pay(Request $request)
    {
        abort_if(
            !Auth::user()->hasRole('Admin') &&
                !Auth::user()->can('make-contribution-payment'),
            403
        );

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:installment,full'
        ]);

        $user = Auth::user();
        $settings = ContributionSetting::firstOrFail();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get current contribution
        $contribution = MonthlyContribution::firstOrCreate(
            [
                'user_id' => $user->id,
                'month' => $currentMonth,
                'year' => $currentYear,
            ],
            [
                'amount_due' => $settings->monthly_amount,
                'total_amount' => $settings->monthly_amount,
                'paid_amount' => 0,
                'status' => 'unpaid',
                'penalty' => 0,
            ]
        );

        // Ensure totals are up-to-date
        if (method_exists($contribution, 'refreshTotals')) {
            $contribution->refreshTotals();
        }

        // Calculate balance including penalties
        $balance = $contribution->total_amount - $contribution->paid_amount;

        // Prevent overpayment
        if ($request->amount > $balance) {
            return back()->withErrors([
                'amount' => 'You cannot pay more than the remaining balance of ' . number_format($balance, 2)
            ]);
        }

        // Record payment
        ContributionPayment::create([
            'user_id' => $user->id,
            'contribution_id' => $contribution->id,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'paid_at' => now()
        ]);

        // Update contribution
        $contribution->paid_amount += $request->amount;

        if ($contribution->paid_amount >= $contribution->total_amount) {
            $contribution->status = 'paid';
        }

        $contribution->save();

        return redirect()
            ->route('backend.admin.contributions.payments.index')
            ->with('success', 'Contribution payment recorded successfully');
    }


    // ================= VIEW SINGLE CONTRIBUTION PAYMENTS =================
    public function showContributionPayments($contributionId)
    {
        $user = Auth::user();
        $contribution = MonthlyContribution::findOrFail($contributionId);

        // Security check
        if ($contribution->user_id !== $user->id) {
            abort(403);
        }

        $payments = $contribution->payments()->latest()->get();

        // Compute total contributed by the user
        $totalContributed = MonthlyContribution::where('user_id', $user->id)->sum('total_amount');

        return view(
            'backend.contributions.member_contributions',
            compact('payments', 'contribution', 'user', 'totalContributed')
        );
    }
}
