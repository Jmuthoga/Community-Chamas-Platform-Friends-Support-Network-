@extends('backend.master')

@section('title', 'Contribution Settings')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Contribution Settings</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('backend.admin.contributions.settings.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Monthly Amount</label>
                <input type="number" name="monthly_amount" class="form-control" value="{{ $settings->monthly_amount }}">
            </div>
            <div class="form-group">
                <label>Penalty Per Day</label>
                <input type="number" name="penalty_per_day" class="form-control" value="{{ $settings->penalty_per_day }}">
            </div>
            <div class="form-group">
                <label>Due Day</label>
                <input type="number" name="due_day" class="form-control" value="{{ $settings->due_day }}">
            </div>
            <div class="form-group">
                <label>Grace Day</label>
                <input type="number" name="grace_day" class="form-control" value="{{ $settings->grace_day }}">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
        </form>
    </div>
</div>
@endsection
