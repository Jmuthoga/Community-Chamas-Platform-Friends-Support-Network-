@extends('backend.master')

@section('title', 'Financial Overview')

@section('content')
<div class="container-fluid">

    {{-- ================= TREASURER CARDS (KCB MasterCard Style) ================= --}}
    <div class="row mb-4">
        {{-- Total Income Card --}}
        <div class="col-md-4">
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front kcb-card">
                        <div class="card-chip"></div>
                        <h6>Total Income</h6>
                        <h3>KES {{ number_format($totalIncome, 2) }}</h3>
                        <div class="card-number">**** **** **** 1234</div>
                        <div class="card-holder">Treasurer</div>
                    </div>
                    <div class="flip-card-back">
                        <h6>Details</h6>
                        <p>All contributions received this year</p>
                        <small>Last updated: {{ now()->format('d M Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Expenses Card --}}
        <div class="col-md-4">
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front kcb-card">
                        <div class="card-chip"></div>
                        <h6>Total Expenses</h6>
                        <h3>KES {{ number_format($totalExpenses, 2) }}</h3>
                        <div class="card-number">**** **** **** 5678</div>
                        <div class="card-holder">Treasurer</div>
                    </div>
                    <div class="flip-card-back">
                        <h6>Details</h6>
                        <p>All Expenses made this year</p>
                        <small>Last updated: {{ now()->format('d M Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Current Bank Balance Card --}}
        <div class="col-md-4">
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front kcb-card">
                        <div class="card-chip"></div>
                        <h6>Current Bank Balance</h6>
                        <h3>KES {{ number_format($balance, 2) }}</h3>
                        <div class="card-number">**** **** **** 9012</div>
                        <div class="card-holder">Treasurer</div>
                    </div>
                    <div class="flip-card-back">
                        <h6>Details</h6>
                        <p>Balance = Total Income − Total Expenses</p>
                        <small>Last updated: {{ now()->format('d M Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MONTHLY TREND CHART ================= --}}
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Monthly Income vs Expenses</h5>
                </div>
                <div class="card-body">
                    <canvas id="financialTrendChart" style="height:300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= RECENT TRANSACTIONS ================= --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-list mr-2"></i>Recent Transactions</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Reference</th>
                                <th>Type</th>
                                <th>Member</th>
                                <th>Amount (KES)</th>
                                <th>Payment Method</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $index => $txn)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $txn->reference }}</td>
                                    <td>{{ ucfirst($txn->type) }}</td>
                                    <td>{{ $txn->user?->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($txn->amount, 2) }}</td>
                                    <td>{{ strtoupper($txn->payment_method) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($txn->transaction_date)->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No recent transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('style')
<style>
/* ===== KCB Multicurrency Card Flip Style ===== */
.flip-card {
    perspective: 1000px;
    margin-bottom: 1rem;
}
.flip-card-inner {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 1rem;
    text-align: left;
    transition: transform 0.8s, box-shadow 0.8s;
    transform-style: preserve-3d;
}
.flip-card:hover .flip-card-inner {
    transform: rotateY(180deg);
    box-shadow: 0 12px 30px rgba(0,0,0,0.3);
}

/* Front and Back */
.flip-card-front, .flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 1rem;
    backface-visibility: hidden;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: white;
}

/* KCB Card Front */
.kcb-card {
    background: linear-gradient(135deg, #006400 0%, #008000 50%, #00b33c 100%);
    box-shadow: 0 8px 20px rgba(0,0,0,0.25), inset 0 0 30px rgba(255,255,255,0.1);
}

/* Card Back */
.flip-card-back {
    transform: rotateY(180deg);
    background: linear-gradient(135deg, #1a1a1a, #444);
    box-shadow: inset 0 0 15px rgba(255,255,255,0.1);
    text-align: center;
    justify-content: center;
}

/* Chip */
.card-chip {
    width: 50px;
    height: 35px;
    background: linear-gradient(145deg, #FFD700, #FFC107);
    border-radius: 4px;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.4);
}

/* Card Number */
.card-number {
    letter-spacing: 2px;
    font-size: 1.2rem;
    margin-top: auto;
    font-family: 'Courier New', Courier, monospace;
}

/* Card Holder */
.card-holder {
    text-transform: uppercase;
    font-size: 0.8rem;
    opacity: 0.75;
}

/* Card Heading */
.flip-card-front h6, .flip-card-back h6 {
    font-weight: 500;
}
</style>
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('financialTrendChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($months ?? []),
        datasets: [
            { label: 'Income', data: @json(array_map(fn($m) => $m->total ?? 0, $monthlyIncome ?? [])), backgroundColor: '#28a745' },
            { label: 'Expenses', data: @json(array_map(fn($m) => $m->total ?? 0, $monthlyExpenses ?? [])), backgroundColor: '#dc3545' }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { tooltip: { mode: 'index', intersect: false }, legend: { position: 'top' } },
        scales: { x: { stacked: false }, y: { beginAtZero: true } }
    }
});
</script>
@endpush