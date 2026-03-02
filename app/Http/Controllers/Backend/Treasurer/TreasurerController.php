<?php

namespace App\Http\Controllers\Backend\Treasurer;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\FinancialTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TreasurerController extends Controller
{
    // ================= FINANCIAL OVERVIEW =================
    public function dashboard()
    {
        abort_if(!Auth::user()->can('treasurer_dashboard'), 403);

        // Total Income & Expenses
        $totalIncome = FinancialTransaction::where('type', 'contribution')->sum('amount');
        $totalExpenses = FinancialTransaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpenses; // current bank balance

        // Get the 10 most recent transactions
        $recentTransactions = FinancialTransaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('backend.treasurer.dashboard', compact(
            'totalIncome',
            'totalExpenses',
            'balance',
            'recentTransactions'
        ));
    }
    // ================= ALL TRANSACTIONS =================
    public function transactions(Request $request)
    {
        abort_if(!Auth::user()->can('treasurer_transactions'), 403);

        if ($request->ajax()) {

            $transactions = FinancialTransaction::with('user')->latest();

            // Apply month filter if selected
            if ($request->filled('month')) {
                [$year, $month] = explode('-', $request->month);
                $transactions->whereYear('transaction_date', $year)
                            ->whereMonth('transaction_date', $month);
            }

            return datatables()
                ->eloquent($transactions)
                ->addIndexColumn()
                ->addColumn('member', fn($row) => $row->user?->name ?? 'Non Member')
                ->addColumn('amount', fn($row) => number_format($row->amount, 2))
                ->addColumn('date', fn($row) => \Carbon\Carbon::parse($row->transaction_date)->format('d M Y'))
                ->addColumn('type', fn($row) => ucfirst($row->type))
                ->addColumn('payment_method', fn($row) => strtoupper($row->payment_method))
                ->rawColumns(['member'])
                ->make(true);
        }

        return view('backend.treasurer.transactions');
    }

    // ================= EXPENSES =================
    public function expenses(Request $request)
    {
        abort_if(!Auth::user()->can('treasurer_expenses'), 403);

        if ($request->ajax()) {

            $expenses = Expense::with('user')->latest();

            return datatables()
                ->eloquent($expenses)
                ->addIndexColumn()
                ->addColumn('member', fn($row) => $row->user?->name ?? 'General Expense')
                ->addColumn('amount', fn($row) => number_format($row->amount, 2))
                ->addColumn('date', fn($row) => $row->expense_date)
                ->make(true);
        }

        return view('backend.treasurer.expenses');
    }

        // ================= CREATE EXPENSE =================
    public function createExpense()
    {
        abort_if(!Auth::user()->can('treasurer_expenses_create'), 403);

        return view('backend.treasurer.create');
    }

    // ================= STORE EXPENSE =================
    public function storeExpense(Request $request)
    {
        abort_if(!Auth::user()->can('treasurer_expenses_create'), 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create([
            'title' => $request->title,
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'description' => $request->description,
        ]);

        return redirect()->route('backend.admin.treasurer.expenses')->with('success', 'Expense added successfully!');
    }

    // ================= FINANCIAL REPORT =================
    public function reports()
    {
        abort_if(!Auth::user()->can('treasurer_reports'), 403);

        $monthlyIncome = FinancialTransaction::where('type', 'contribution')
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyExpenses = FinancialTransaction::where('type', 'expense')
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('backend.treasurer.reports', compact('monthlyIncome', 'monthlyExpenses'));
    }
}