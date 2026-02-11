<?php

namespace App\Http\Controllers\Backend\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

use App\Models\ContributionPayment;
use App\Models\MonthlyContribution;
use App\Services\ContributionService;

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
                    return $row->contribution->month . ' / ' . $row->contribution->year;
                })
                ->addColumn('amount', fn($row) => number_format($row->amount, 2))
                ->addColumn('contribution_id', fn($row) => $row->contribution_id)
                ->addColumn('paid_at', function ($row) {
                    return $row->paid_at
                        ? $row->paid_at->format('d M Y H:i:s')
                        : '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->contribution->status === 'paid'
                        ? '<span class="badge badge-success">Completed</span>'
                        : '<span class="badge badge-warning">Installment</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('backend.contributions.payments');
    }


    // ================= MAKE CONTRIBUTION PAYMENT =================
    public function pay(Request $request, ContributionService $service)
    {
        abort_if(
            !Auth::user()->hasRole('Admin') &&
            !Auth::user()->can('make-contribution-payment'),
            403
        );

        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $service->makeContribution(
            Auth::user(),
            $request->amount
        );

        return redirect()
            ->route('backend.admin.contributions.payments.index')
            ->with('success', 'Contribution recorded successfully');
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

    public function create()
    {
        $userId = Auth::id();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get existing contribution or create default for current month
        $contribution = MonthlyContribution::firstOrCreate(
            [
                'user_id' => $userId,
                'month' => $currentMonth,
                'year' => $currentYear,
            ],
            [
                'amount_due' => 500,       // default monthly amount
                'total_amount' => 500,     // default total
                'status' => 'unpaid',
                'total_paid' => 0,
                'penalty' => 0,
            ]
        );

        return view('backend.contributions.create_payment', compact('contribution'));
    }

}
