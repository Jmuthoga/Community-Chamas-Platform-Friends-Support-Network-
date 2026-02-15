@extends('backend.master')

@section('title','Dashboard')

@section('content')
<section class="content">
<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Welcome back, {{ Auth::user()->name }} 👋</h4>
</div

@can('dashboard_view')

@php
function percentage($value,$target){
    return $target>0 ? min(($value/$target)*100,100) : 0;
}
@endphp

{{-- ===== MAIN CONTRIBUTION STATS ===== --}}
<div class="row">

    {{-- Monthly Collected --}}
    <div class="col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1">
                <i class="fas fa-money-bill"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Monthly Collected</span>
                <span class="info-box-number">
                    {{ currency()->symbol ?? '' }} {{ number_format($monthlyCollected,2) }}
                </span>
                <div class="progress">
                    <div class="progress-bar bg-success"
                        style="width: {{ percentage($monthlyCollected,$monthlyTarget) }}%">
                    </div>
                </div>
                <small>{{ round(percentage($monthlyCollected,$monthlyTarget)) }}% of Target</small>
            </div>
        </div>
    </div>

    {{-- Monthly Penalties --}}
    <div class="col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Monthly Penalties</span>
                <span class="info-box-number">
                    {{ currency()->symbol ?? '' }} {{ number_format($monthlyPenalties,2) }}
                </span>
                <div class="progress">
                    <div class="progress-bar bg-danger"
                        style="width: {{ percentage($monthlyPenalties,$monthlyPenalties) }}%"></div>
                </div>
                <small>{{ round(percentage($monthlyPenalties,$monthlyPenalties)) }}% of Target</small>
            </div>
        </div>
    </div>

    {{-- Members Contributed --}}
    <div class="col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-primary elevation-1">
                <i class="fas fa-users"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Members Contributed</span>
                <span class="info-box-number">{{ $membersContributed }} / {{ $totalMembers }}</span>
                <div class="progress">
                    <div class="progress-bar bg-primary"
                        style="width: {{ percentage($membersContributed,$totalMembers) }}%"></div>
                </div>
                <small>{{ round(percentage($membersContributed,$totalMembers)) }}%</small>
            </div>
        </div>
    </div>

    {{-- Monthly Remaining Balance --}}
    <div class="col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1">
                <i class="fas fa-wallet"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Monthly Remaining Balance</span>
                <span class="info-box-number">{{ currency()->symbol ?? '' }} {{ number_format($monthlyRemainingBalance,2) }}</span>
                <div class="progress">
                    <div class="progress-bar bg-warning"
                        style="width: {{ percentage($monthlyRemainingBalance,$monthlyTarget) }}%"></div>
                </div>
                <small>{{ round(percentage($monthlyRemainingBalance,$monthlyTarget)) }}% of Target</small>
            </div>
        </div>
    </div>

</div>

{{-- ===== SECOND ROW (SUMMARY BOXES) WITH MOVING FSN TEXT ===== --}}
<div class="row fsn-marquee">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info position-relative overflow-hidden">
            <div class="inner">
                <h3>{{ currency()->symbol ?? '' }} {{ number_format($allTimeCollected,2) }}</h3>
                <p>All Time Contributions</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger position-relative overflow-hidden">
            <div class="inner">
                <h3>{{ currency()->symbol ?? '' }} {{ number_format($allTimePenalties,2) }}</h3>
                <p>All Time Penalties</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success position-relative overflow-hidden">
            <div class="inner">
                <h3>{{ $totalPayments }}</h3>
                <p>Total Payments</p>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary position-relative overflow-hidden">
            <div class="inner">
                <h3>{{ $fullyPaidMonths }}</h3>
                <p>Fully Paid Contributions</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Moving FRIENDS SUPPORT NETWORK (FSN) background */
.fsn-marquee .small-box::before {
    content: "{{ readConfig('site_name') }}  {{ readConfig('site_name') }}  {{ readConfig('site_name') }}";
    position: absolute;
    font-size: 2rem;
    top: 50%;
    left: 100%;
    transform: translateY(-50%);
    white-space: nowrap;
    opacity: 0.1;
    pointer-events: none;
    animation: move-fsn 20s linear infinite;
}

@keyframes move-fsn {
    0% { left: 100%; }
    100% { left: -100%; }
}
</style>

{{-- ===== CHARTS ROW ===== --}}
<div class="row">

    {{-- Monthly Contributions Chart --}}
    <div class="col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
                <h5>Monthly Contributions</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyContributionChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Members Contribution % Chart --}}
    <div class="col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
                <h5>Members Contribution Percentage</h5>
            </div>
            <div class="card-body">
                <canvas id="membersPercentageChart"></canvas>
            </div>
        </div>
    </div>

</div>

@endcan
</div>
</section>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('monthlyContributionChart'),{
    type:'bar',
    data:{
        labels: @json($months ?? []),
        datasets:[{
            label:'Monthly Contributions',
            data: @json($monthlyCollectedPerMonth ?? []),
            backgroundColor:'#1e40af'
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio: false,
        scales:{ y:{ beginAtZero:true } }
    }
});

new Chart(document.getElementById('membersPercentageChart'),{
    type:'doughnut',
    data:{
        labels:['Contributed','Pending'],
        datasets:[{
            label:'Members Contribution',
            data:[{{ $membersContributed }}, {{ $totalMembers - $membersContributed }}],
            backgroundColor:['#007bff','#ffc107']
        }]
    },
    options:{ responsive:true, maintainAspectRatio:false }
});
</script>
@endpush
