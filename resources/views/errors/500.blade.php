@extends('backend.master')

@section('title', '500 | Internal Server Error')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card text-center p-5" style="max-width: 600px; width: 100%; box-shadow: 0 12px 30px rgba(0,0,0,0.1); border-radius: 12px;">

        <!-- Large Centered Image -->
        <img src="{{ asset('assets/images/500-error.png') }}" alt="Internal Server Error" class="img-fluid mb-4" style="max-width: 300px; display: block; margin: 0 auto;">

        <h2 class="mb-3 fw-bold text-danger">Internal Server Error</h2>

        {{-- Show professional user information --}}
        @auth
        <p class="text-muted mb-3">
            You are logged in as <strong>{{ auth()->user()->name }}</strong>.
            An error occurred while attempting to <strong>{{ $action ?? 'perform this operation' }}</strong>.
        </p>
        @else
        <p class="text-muted mb-3">
            An error occurred while attempting to <strong>{{ $action ?? 'perform this operation' }}</strong>.
        </p>
        @endauth

        {{-- Show the error message --}}
        @if(isset($exception) && $exception->getMessage())
        <div class="alert alert-danger text-start" role="alert" style="font-size: 14px;">
            <strong>Error Details:</strong> {{ $exception->getMessage() }}
        </div>

        @php
        // Determine if this error requires developer intervention
        $requiresDeveloper = false;

        if($exception instanceof \Illuminate\Database\QueryException ||
        $exception instanceof \Symfony\Component\Debug\Exception\FatalThrowableError ||
        $exception instanceof \ErrorException ||
        $exception instanceof \Exception) {
        $requiresDeveloper = true;
        }
        @endphp

        @if($requiresDeveloper)
        <p class="text-muted mb-3">
            This error requires developer attention. Please contact the
            <strong>JM Innovatech Support Team</strong> at
            <a href="tel:0791446968">0791 446 968</a>.
        </p>
        @else
        <p class="text-muted mb-3">
            This may be a temporary issue. You can try refreshing the page to continue.
        </p>
        @endif
        @else
        <p class="text-muted mb-3">
            The system encountered an unexpected condition. Refreshing the page may resolve it.
            If the issue persists, please contact the
            <strong>JM Innovatech Support Team</strong> at
            <a href="tel:0791446968">0791 446 968</a>.
        </p>
        @endif

        <div class="d-flex justify-content-center mb-3 gap-2">
            <a href="{{ url('/') }}" class="btn btn-primary px-4">Go to Homepage</a>
            <a href="javascript:location.reload()" class="btn btn-secondary px-4">Refresh Page</a>
        </div>

        <small class="text-muted d-block">Error Code: 500</small>

    </div>
</div>
@endsection@extends('backend.master')

@section('title', '500 | Internal Server Error')

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card text-center p-5" style="max-width: 600px; width: 100%; box-shadow: 0 12px 30px rgba(0,0,0,0.1); border-radius: 12px;">

        <!-- Large Centered Image -->
        <img src="{{ asset('assets/images/500-error.png') }}" alt="Internal Server Error" class="img-fluid mb-4" style="max-width: 300px; display: block; margin: 0 auto;">

        <h2 class="mb-3 fw-bold text-danger">Internal Server Error</h2>

        {{-- Show professional user information --}}
        @auth
        <p class="text-muted mb-3">
            You are logged in as <strong>{{ auth()->user()->name }}</strong>.
            An error occurred while attempting to <strong>{{ $action ?? 'perform this operation' }}</strong>.
        </p>
        @else
        <p class="text-muted mb-3">
            An error occurred while attempting to <strong>{{ $action ?? 'perform this operation' }}</strong>.
        </p>
        @endauth

        {{-- Show the error message --}}
        @if(isset($exception) && $exception->getMessage())
        <div class="alert alert-danger text-start" role="alert" style="font-size: 14px;">
            <strong>Error Details:</strong> {{ $exception->getMessage() }}
        </div>

        @php
        // Determine if this error requires developer intervention
        $requiresDeveloper = false;

        if($exception instanceof \Illuminate\Database\QueryException ||
        $exception instanceof \Symfony\Component\Debug\Exception\FatalThrowableError ||
        $exception instanceof \ErrorException ||
        $exception instanceof \Exception) {
        $requiresDeveloper = true;
        }
        @endphp

        @if($requiresDeveloper)
        <p class="text-muted mb-3">
            This error requires developer attention. Please contact the
            <strong>JM Innovatech Support Team</strong> at
            <a href="tel:0791446968">0791 446 968</a>.
        </p>
        @else
        <p class="text-muted mb-3">
            This may be a temporary issue. You can try refreshing the page to continue.
        </p>
        @endif
        @else
        <p class="text-muted mb-3">
            The system encountered an unexpected condition. Refreshing the page may resolve it.
            If the issue persists, please contact the
            <strong>JM Innovatech Support Team</strong> at
            <a href="tel:0791446968">0791 446 968</a>.
        </p>
        @endif

        <div class="d-flex justify-content-center mb-3 gap-2">
            <a href="{{ url('/') }}" class="btn btn-primary px-4">Go to Homepage</a>
            <a href="javascript:location.reload()" class="btn btn-secondary px-4">Refresh Page</a>
        </div>

        <small class="text-muted d-block">Error Code: 500</small>

    </div>
</div>
@endsection