<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\FinancialTransaction;

class ExpenseObserver
{
    public function created(Expense $expense)
    {
        $exists = FinancialTransaction::where('source_id', $expense->id)
            ->where('source_type', Expense::class)
            ->exists();

        if ($exists) {
            return;
        }

        FinancialTransaction::create([
            'user_id' => $expense->user_id,
            'reference' => 'EXP-' . $expense->id,
            'type' => 'expense',
            'amount' => $expense->amount,
            'description' => $expense->title,
            'payment_method' => 'cash',
            'transaction_date' => $expense->expense_date ?? now(),
            'source_id' => $expense->id,
            'source_type' => Expense::class,
        ]);
    }
}
