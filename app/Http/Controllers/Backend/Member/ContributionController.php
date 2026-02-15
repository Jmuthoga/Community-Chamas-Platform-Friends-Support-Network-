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
use App\Services\MpesaService;
use App\Mail\ContributionNotificationMail;
use App\Mail\ContributionSummaryMail;
use Illuminate\Support\Facades\Mail;


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
                })gc
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

    public function pay(Request $request, MpesaService $mpesa)
    {
        abort_if(
            !Auth::user()->hasRole('Admin') &&
                !Auth::user()->can('make-contribution-payment'),
            403
        );

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_type' => 'required|in:installment,full',
            'payment_method' => 'required|in:cash,mpesa',
            'mpesa_phone' => 'nullable|string'
        ]);

        $user = Auth::user();
        $settings = ContributionSetting::firstOrFail();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        // ================= GET CONTRIBUTION =================
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

        // Refresh totals including penalties
        if (method_exists($contribution, 'refreshTotals')) {
            $contribution->refreshTotals();
        }

        $balance = $contribution->total_amount - $contribution->paid_amount;

        // ================= PREVENT OVERPAYMENT =================
        if ($request->amount > $balance) {
            return back()->withErrors([
                'amount' => 'You cannot pay more than remaining balance of ' . number_format($balance, 2)
            ]);
        }

        /*
        |----------------------------------------------------------------------
        | CASH PAYMENT
        |----------------------------------------------------------------------
        */
        if ($request->payment_method === 'cash') {

            ContributionPayment::create([
                'user_id' => $user->id,
                'contribution_id' => $contribution->id,
                'amount' => $request->amount,
                'payment_type' => $request->payment_type,
                'paid_at' => now(),
                'status' => 'completed'
            ]);

            $contribution->paid_amount += $request->amount;

            if ($contribution->paid_amount >= $contribution->total_amount) {
                $contribution->status = 'paid';
            }

            $contribution->save();

            // ================= SEND EMAIL WITH LIVE MONTHLY STATS =================
            $monthYear = $contribution->month . ' / ' . $contribution->year;
            $totalAllTime = \App\Models\MonthlyContribution::sum('paid_amount');
            $totalPenalties = \App\Models\MonthlyContribution::where('month', now()->month)
                                ->where('year', now()->year)
                                ->sum('penalty');
            
            $mailData = [
                'user' => $user,
                'name' => $user->name,
                'userName' => $user->name,
                'monthYear' => $monthYear,
                'amount' => $request->amount,
                'payment_type' => $request->payment_type,
                'dashboardUrl' => route('backend.admin.contributions.payments.index'),
                'totalAllTime' => $totalAllTime,
                'totalPenalties' => $totalPenalties,
            ];
                        
            // Merge live monthly stats
            $mailData = array_merge($mailData, $this->getMonthlyStats());

            
            // Send notification
            Mail::to($user->email)->queue(new ContributionNotificationMail($mailData));
            
            // Send summary if fully paid
            if ($this->isMonthFullyPaid()) {
            
                $users = \App\Models\User::pluck('email');
            
                foreach ($users as $email) {
                    Mail::to($email)->queue(new ContributionSummaryMail($mailData));
                }
            }

            return redirect()
                ->route('backend.admin.contributions.payments.index')
                ->with('success', 'Contribution payment recorded successfully');
        }

        /*
        |----------------------------------------------------------------------
        | MPESA PAYMENT
        |----------------------------------------------------------------------
        */

        // Determine phone
        $phone = $request->mpesa_phone ?: $user->phone;
        
        // Normalize phone format
        $phone = preg_replace('/\D/', '', $phone);
        
        if (substr($phone, 0, 1) == '0') {
            $phone = '254' . substr($phone, 1);
        }
        
        if (substr($phone, 0, 3) == '254') {
            // Valid Kenyan format
        }

        // Send STK Push
        $stkResponse = $mpesa->stkPush(
            $phone,
            $request->amount,
            "CONTRIB-" . $contribution->id,
            "Contribution Payment"
        );

        // Check if Safaricom accepted request
        if (!isset($stkResponse['CheckoutRequestID'])) {
            return back()->withErrors([
                'mpesa' => 'Failed to initiate MPESA request. Try again.'
            ]);
        }

        // Save pending payment
        ContributionPayment::create([
            'user_id' => $user->id,
            'contribution_id' => $contribution->id,
            'amount' => $request->amount,
            'payment_type' => $request->payment_type,
            'phone' => $phone,
            'checkout_request_id' => $stkResponse['CheckoutRequestID'],
            'status' => 'pending'
        ]);

        return back()->with('success', 'STK Push sent. Please complete payment on your phone.');
    }

    public function stkPush(Request $request, MpesaService $mpesa)
    {
        $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|numeric|min:1'
        ]);

        $user = Auth::user();
        $settings = ContributionSetting::firstOrFail();

        $currentMonth = now()->month;
        $currentYear = now()->year;

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

        if (method_exists($contribution, 'refreshTotals')) {
            $contribution->refreshTotals();
        }

        $balance = $contribution->total_amount - $contribution->paid_amount;

        if ($request->amount > $balance) {
            return response()->json([
                'error' => 'Cannot pay more than remaining balance of ' . number_format($balance, 2)
            ], 422);
        }

        // Normalize phone format
        $phone = preg_replace('/\D/', '', $request->phone);
        
        if (substr($phone, 0, 1) == '0') {
            $phone = '254' . substr($phone, 1);
        }
        
        if (substr($phone, 0, 3) == '254') {
            // Valid Kenyan format
        }

        $stkResponse = $mpesa->stkPush(
            $phone,
            $request->amount,
            "CONTRIB-" . $contribution->id,
            "Contribution Payment"
        );

        if (!isset($stkResponse['CheckoutRequestID'])) {
            return response()->json([
                'error' => 'Failed to initiate MPESA request.'
            ], 500);
        }

        ContributionPayment::create([
            'user_id' => $user->id,
            'contribution_id' => $contribution->id,
            'amount' => $request->amount,
            'payment_type' => 'installment', // or fetch from request if needed
            'phone' => $phone,
            'checkout_request_id' => $stkResponse['CheckoutRequestID'],
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'STK Push sent',
            'checkout_request_id' => $stkResponse['CheckoutRequestID']
        ]);
    }
    
    // ================= MPESA STK CALLBACK =================
    public function handleStkCallback(Request $request)
    {
        $payload = $request->all();

        \Log::info('MPESA CALLBACK:', $payload);

        $callback = $payload['Body']['stkCallback'] ?? null;

        if (!$callback) {
            return response()->json(['message' => 'Invalid callback']);
        }

        $checkoutId = $callback['CheckoutRequestID'];
        $resultCode = $callback['ResultCode'];

        // Find pending payment
        $payment = ContributionPayment::where('checkout_request_id', $checkoutId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found']);
        }

        // Get user from payment
        $user = $payment->user;

        /*
        |--------------------------------------------------------------------------
        | SUCCESSFUL PAYMENT
        |--------------------------------------------------------------------------
        */
        if ($resultCode == 0) {

            $items = collect($callback['CallbackMetadata']['Item'] ?? []);

            $receipt = optional(
                $items->firstWhere('Name', 'MpesaReceiptNumber')
            )['Value'];

            $payment->update([
                'mpesa_receipt' => $receipt,
                'status' => 'completed',
                'paid_at' => now()
            ]);

            // Update contribution totals
            $contribution = MonthlyContribution::find($payment->contribution_id);

            if ($contribution) {

                $contribution->paid_amount += $payment->amount;

                if ($contribution->paid_amount >= $contribution->total_amount) {
                    $contribution->status = 'paid';
                }

                $contribution->save();

                // Prepare email data
                $monthYear = optional($contribution)->month . ' / ' . optional($contribution)->year;
                $totalAllTime = \App\Models\MonthlyContribution::sum('paid_amount');
                $totalPenalties = \App\Models\MonthlyContribution::where('month', now()->month)
                                    ->where('year', now()->year)
                                    ->sum('penalty');

                $mailData = [
                    'user' => $user,
                    'name' => $user->name,
                    'userName' => $user->name,
                    'monthYear' => $monthYear,
                    'amount' => $payment->amount,
                    'payment_type' => $payment->payment_type,
                    'dashboardUrl' => route('backend.admin.contributions.payments.index'),
                    'totalAllTime' => $totalAllTime,
                    'totalPenalties' => $totalPenalties
                ];

                // Merge live monthly stats
                $mailData = array_merge($mailData, $this->getMonthlyStats());

                // Send notification
                Mail::to($user->email)->queue(new ContributionNotificationMail($mailData));

                // Send summary if fully paid
                if ($this->isMonthFullyPaid()) {
                
                    $users = \App\Models\User::pluck('email');
                
                    foreach ($users as $email) {
                        Mail::to($email)->queue(new ContributionSummaryMail($mailData));
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FAILED / CANCELLED PAYMENT
        |--------------------------------------------------------------------------
        */
        else {
            $payment->update([
                'status' => 'failed'
            ]);
        }

        return response()->json(['message' => 'Callback processed']);
    }


    // ================= CHECK PAYMENT STATUS =================
    public function checkPaymentStatus($checkoutId)
    {
        $payment = ContributionPayment::where('checkout_request_id', $checkoutId)->first();
    
        if (!$payment) {
            return response()->json(['status' => 'not_found']);
        }
    
        return response()->json([
            'status' => $payment->status,
            'receipt' => $payment->mpesa_receipt
        ]);
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

    // ================= HELPER: GET LIVE MONTHLY STATS =================
    private function getMonthlyStats()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $totalMembers = \App\Models\User::count();
        $contributedCount = \App\Models\MonthlyContribution::where('month', $currentMonth)
                            ->where('year', $currentYear)
                            ->where('paid_amount', '>', 0)
                            ->count();
        $remainingCount = $totalMembers - $contributedCount;
        $totalCollected = \App\Models\MonthlyContribution::where('month', $currentMonth)
                            ->where('year', $currentYear)
                            ->sum('paid_amount');
        $remainingBalance = \App\Models\MonthlyContribution::where('month', $currentMonth)
                            ->where('year', $currentYear)
                            ->sum(\DB::raw('total_amount - paid_amount'));

        return compact('totalMembers','contributedCount','remainingCount','totalCollected','remainingBalance');
    }
    
    private function isMonthFullyPaid()
    {
        $month = now()->month;
        $year = now()->year;
    
        $totalMembers = \App\Models\User::count();
    
        $fullyPaidMembers = \App\Models\MonthlyContribution::where('month', $month)
            ->where('year', $year)
            ->whereColumn('paid_amount', '>=', 'total_amount')
            ->count();
    
        return $totalMembers === $fullyPaidMembers;
    }

}
