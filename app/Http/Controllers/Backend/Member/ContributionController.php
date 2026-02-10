<?php

namespace App\Http\Controllers\Backend\Member;

use App\Models\User;
use App\Models\ContributionSetting;
use App\Models\MonthlyContribution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ContributionController extends Controller
{
    // ================= MEMBER / ADMIN VIEW =================
    public function index(Request $request)
    {
        $user = Auth::user();

        // MEMBER: can see only their own contributions unless they have special permission
        if ($user->hasRole('Member') && !$user->can('view-other-contributions')) {
            $contributions = MonthlyContribution::where('user_id', $user->id)
                ->latest()
                ->get();

            return view('backend.contributions.index', compact('contributions'));
        }

        // ADMIN / Member with permission: can see all
        abort_if(!Auth::user()->can('view-contribution-view'), 403);

        if ($request->ajax()) {
            $contributions = MonthlyContribution::with('user')->latest()->get();

            return DataTables::of($contributions)
                ->addIndexColumn()
                ->addColumn('user', fn($row) => $row->user->name)
                ->addColumn('month', fn($row) => $row->month . ' / ' . $row->year)
                ->addColumn('amount_due', fn($row) => number_format($row->amount_due, 2))
                ->addColumn('penalty', fn($row) => number_format($row->penalty, 2))
                ->addColumn('total_amount', fn($row) => number_format($row->total_amount, 2))
                ->addColumn('status', function ($row) {
                    return $row->status === 'paid'
                        ? '<span class="badge badge-success">Paid</span>'
                        : '<span class="badge badge-danger">Unpaid</span>';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="btn-group" role="group">';

                    // View button
                    if (auth()->user()->can('view-contribution-current')) {
                        $buttons .= '<a href="' . route("backend.admin.contributions.member.view", ['user' => $row->user_id]) . '" class="btn btn-sm btn-primary">View</a>';
                    }

                    // Edit button (optional)
                    if (auth()->user()->can('update-contribution')) {
                        $buttons .= '<a href="#" class="btn btn-sm btn-warning">Edit</a>';
                    }

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('backend.contributions.all');
    }

    // ================= MEMBER / ADMIN - VIEW MEMBER CONTRIBUTIONS =================
    public function showMemberContributions(Request $request, User $user)
    {
        // Optional: allow admin to bypass
        if (Auth::id() !== $user->id && !Auth::user()->can('view-other-contributions')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            abort(403);
        }

        if ($request->ajax() || $request->wantsJson()) {
            try {
                $query = MonthlyContribution::where('user_id', $user->id)
                    ->orderBy('year')
                    ->orderBy('month');

                // Use the helper function datatables() instead of static call
                return datatables()
                    ->eloquent($query)
                    ->addIndexColumn()
                    ->addColumn('month_year', fn($row) => $row->month . ' / ' . $row->year)
                    ->addColumn('amount_due', fn($row) => number_format($row->amount_due, 2))
                    ->addColumn('penalty', fn($row) => number_format($row->penalty, 2))
                    ->addColumn('total_paid', fn($row) => number_format($row->total_amount, 2))
                    ->addColumn('payment_type', fn($row) => $row->status === 'paid' ? 'Full Payment' : 'Installment')
                    ->addColumn('status', fn($row) => $row->status === 'paid'
                        ? '<span class="badge badge-success">Paid</span>'
                        : '<span class="badge badge-danger">Unpaid</span>')
                    ->addColumn('payment_date', function ($row) {
                        return $row->paid_at ? \Carbon\Carbon::parse($row->paid_at)->format('d M Y') : '-';
                    })
                    ->rawColumns(['status'])
                    ->make(true);
            } catch (\Exception $e) {
                // Send JSON error for debugging DataTables
                return response()->json([
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
        }

        // Non-AJAX view
        $totalContributed = MonthlyContribution::where('user_id', $user->id)->sum('total_amount');

        return view('backend.contributions.member_contributions', compact('user', 'totalContributed'));
    }


    // ================= SETTINGS =================
    public function settings()
    {
        abort_if(!Auth::user()->can('website_settings'), 403);
        $settings = ContributionSetting::first();
        return view('backend.contributions.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        abort_if(!Auth::user()->can('website_settings'), 403);
        $settings = ContributionSetting::first();

        $request->validate([
            'monthly_amount' => 'required|numeric',
            'penalty_per_day' => 'required|numeric',
            'due_day' => 'required|integer',
            'grace_day' => 'required|integer',
        ]);

        $settings->update($request->only([
            'monthly_amount',
            'penalty_per_day',
            'due_day',
            'grace_day'
        ]));

        return back()->with('success', 'Contribution settings updated successfully');
    }
}
