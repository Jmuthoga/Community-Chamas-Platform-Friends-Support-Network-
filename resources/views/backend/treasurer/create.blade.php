@extends('backend.master')

@section('title', 'Add Expense')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0"><i class="fas fa-plus-circle mr-2"></i> Add New Expense</h3>
        <a href="{{ route('backend.admin.treasurer.expenses') }}" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Expenses
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('backend.admin.treasurer.expenses.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Expense Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="title" placeholder="Enter expense title" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">Member (optional)</label>
                    <select class="form-control" name="user_id">
                        <option value="">General Expense</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="amount" class="form-label">Amount (KES) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="amount" step="0.01" placeholder="0.00" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="expense_date" class="form-label">Expense Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="expense_date" value="{{ now()->toDateString() }}" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description (optional)</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Add a note about this expense"></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn bg-gradient-primary px-4">
                    <i class="fas fa-save mr-1"></i> Save Expense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection