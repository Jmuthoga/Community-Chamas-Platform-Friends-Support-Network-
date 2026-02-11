@extends('backend.master')

@section('title', 'Contribution Settings Overview')

@section('content')
<div class="container-fluid">

    {{-- Main Card --}}
    <div class="card border-0 shadow-lg rounded-4 mt-4">
        <div class="card-body p-4">

            {{-- Header Section --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-5">

                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        <i class="fas fa-users-cog text-primary me-2"></i>
                        Group Contribution Agreement
                    </h4>
                    <p class="text-muted mb-0">
                        Official financial agreement adopted by group members
                    </p>
                </div>

                <div class="mt-2 mt-md-0">
                @can('website_settings') 
                    <a href="{{ route('backend.admin.contributions.settings') }}"
                       class="btn btn-primary btn-sm me-2 shadow-sm">
                        <i class="fas fa-edit me-1"></i> Edit Settings
                    </a>
                @endcan
                    <a href="{{ route('backend.admin.contributions.index') }}"
                       class="btn bg-gradient-secondary btn-sm shadow-sm">
                        <i class="fas fa-ruler-vertical me-1"></i> All Contributions
                    </a>
                </div>

            </div>

            {{-- Agreement Cards --}}
            <div class="row g-4">

                {{-- Monthly Contribution --}}
                <div class="col-xl-3 col-md-6">
                    <div class="agreement-card bg-success-gradient">

                        <div class="agreement-header">
                            <span>Monthly Contribution</span>
                            <i class="fas fa-coins"></i>
                        </div>

                        <div class="agreement-value">
                            KES {{ number_format($settings->monthly_amount, 2) }}
                        </div>

                        <div class="agreement-note">
                            Agreed fixed monthly member contribution
                        </div>

                    </div>
                </div>

                {{-- Penalty --}}
                <div class="col-xl-3 col-md-6">
                    <div class="agreement-card bg-danger-gradient">

                        <div class="agreement-header">
                            <span>Late Penalty</span>
                            <i class="fas fa-exclamation-circle"></i>
                        </div>

                        <div class="agreement-value">
                            KES {{ number_format($settings->penalty_per_day, 2) }}
                        </div>

                        <div class="agreement-note">
                            Charged daily after grace period
                        </div>

                    </div>
                </div>

                {{-- Due Day --}}
                <div class="col-xl-3 col-md-6">
                    <div class="agreement-card bg-primary-gradient">

                        <div class="agreement-header">
                            <span>Due Date</span>
                            <i class="fas fa-calendar-check"></i>
                        </div>

                        <div class="agreement-value">
                            {{ $settings->due_day }}<small class="fs-6">th</small>
                        </div>

                        <div class="agreement-note">
                            Monthly contribution deadline
                        </div>

                    </div>
                </div>

                {{-- Grace Day --}}
                <div class="col-xl-3 col-md-6">
                    <div class="agreement-card bg-warning-gradient">

                        <div class="agreement-header">
                            <span>Grace Period</span>
                            <i class="fas fa-clock"></i>
                        </div>

                        <div class="agreement-value">
                            {{ $settings->grace_day }}<small class="fs-6">th</small>
                        </div>

                        <div class="agreement-note">
                            Final day before penalties begin
                        </div>

                    </div>
                </div>

            </div>

            {{-- Information Section --}}
            <div class="row mt-5 g-4">

                <div class="col-lg-6">
                    <div class="info-box bg-light-primary">
                        <h6>
                            <i class="fas fa-info-circle me-2"></i>
                            Agreement Notes
                        </h6>

                        <p>
                            These contribution terms were mutually agreed by group members.
                            Every member is expected to honour deadlines to ensure smooth
                            financial operations and sustainability of group projects.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="info-box bg-light-warning">
                        <h6>
                            <i class="fas fa-lightbulb me-2"></i>
                            Administration Tips
                        </h6>

                        <p>
                            Update these values only after official member consultation.
                            Notify members early whenever policy adjustments are made.
                        </p>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="text-center text-muted mt-5">
                <small>
                    <i class="fas fa-history me-1"></i>
                    Last updated: {{ now()->format('d M Y - H:i') }}
                </small>
            </div>

        </div>
    </div>
</div>

{{-- Styling --}}
<style>

.agreement-card {
    padding: 28px;
    border-radius: 18px;
    color: white;
    height: 100%;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.agreement-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.15);
}

.agreement-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    opacity: 0.95;
    margin-bottom: 15px;
}

.agreement-header i {
    font-size: 1.5rem;
    opacity: 0.7;
}

.agreement-value {
    font-size: 2.1rem;
    font-weight: 700;
    letter-spacing: 1px;
}

.agreement-note {
    margin-top: 10px;
    font-size: 0.85rem;
    opacity: 0.85;
}

/* Gradient Themes */

.bg-success-gradient {
    background: linear-gradient(135deg,#2e7d32,#66bb6a);
}

.bg-danger-gradient {
    background: linear-gradient(135deg,#c62828,#ef5350);
}

.bg-primary-gradient {
    background: linear-gradient(135deg,#1565c0,#42a5f5);
}

.bg-warning-gradient {
    background: linear-gradient(135deg,#ef6c00,#ffb74d);
}

/* Info Boxes */

.info-box {
    padding: 22px;
    border-radius: 14px;
    height: 100%;
}

.bg-light-primary {
    background: #eef5ff;
}

.bg-light-warning {
    background: #fff6e6;
}

.info-box h6 {
    font-weight: 600;
    margin-bottom: 10px;
}

.info-box p {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 0;
}

/* ✅ Responsive Fix (Prevents Cards From Touching) */
@media (max-width: 768px) {

    .row.g-4 > [class*='col-'] {
        margin-bottom: 20px;
    }

}

</style>

@endsection
