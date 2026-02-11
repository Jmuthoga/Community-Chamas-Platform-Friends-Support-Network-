@extends('backend.master')

@section('title', 'Contribution Settings')

@section('content')
<div class="card shadow-sm border-0 rounded-lg">
    <div class="card-body">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-triangle me-1"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('backend.admin.contributions.settings.update') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Monthly Contribution --}}
                <div class="col-md-6">
                    <label for="monthly_amount" class="form-label fw-bold">Monthly Contribution Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="monthly_amount" id="monthly_amount"
                           value="{{ old('monthly_amount', $settings->monthly_amount) }}"
                           class="form-control @error('monthly_amount') is-invalid @enderror" required>
                    <div class="invalid-feedback">
                        Please enter a valid monthly contribution amount.
                    </div>
                </div>

                {{-- Penalty Per Day --}}
                <div class="col-md-6">
                    <label for="penalty_per_day" class="form-label fw-bold">Penalty Per Day <span class="text-muted">(after grace period)</span></label>
                    <input type="number" step="0.01" name="penalty_per_day" id="penalty_per_day"
                           value="{{ old('penalty_per_day', $settings->penalty_per_day) }}"
                           class="form-control @error('penalty_per_day') is-invalid @enderror" required>
                    <div class="invalid-feedback">
                        Please enter a valid penalty per day.
                    </div>
                </div>

                {{-- Due Day --}}
                <div class="col-md-6">
                    <label for="due_day" class="form-label fw-bold">Due Day of Month <span class="text-danger">*</span></label>
                    <input type="number" name="due_day" id="due_day"
                           value="{{ old('due_day', $settings->due_day) }}"
                           class="form-control @error('due_day') is-invalid @enderror" min="1" max="31" required>
                    <div class="invalid-feedback">
                        Due day must be between 1 and 31.
                    </div>
                </div>

                {{-- Grace Day --}}
                <div class="col-md-6">
                    <label for="grace_day" class="form-label fw-bold">Grace Day <span class="text-muted">(default 16)</span></label>
                    <input type="number" name="grace_day" id="grace_day"
                           value="{{ old('grace_day', $settings->grace_day) }}"
                           class="form-control @error('grace_day') is-invalid @enderror" min="1" max="31" required>
                    <div class="invalid-feedback">
                        Grace day must be between 1 and 31.
                    </div>
                </div>
            </div>
            
            {{-- Buttons --}}
            <div class="mt-4 d-flex flex-wrap justify-content-start">
                <button type="submit" class="btn btn-success btn-sm mb-2" style="margin-right: 10px;">
                    <i class="fas fa-save me-1"></i> Save Settings
                </button>
                <a href="{{ route('backend.admin.contributions.index') }}" class="btn bg-gradient-primary btn-sm mb-2" style="margin-left: 10px;">
                    <i class="fas fa-ruler-vertical me-1"></i> All Contributions
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Client-side validation --}}
<script>
    (function () {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
@endsection
