<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EnsureSubscriptionIsActive
{
    public function handle($request, Closure $next)
    {
        // Allow public routes
        $allowedPaths = [
            'subscription/pay*',
            'subscription/demo*',
            'subscription/callback*',
            'api/subscription/stk-callback*',
        ];

        foreach ($allowedPaths as $path) {
            if ($request->is($path)) {
                return $next($request);
            }
        }

        // Redirect guests to login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Fetch latest active subscription (paid or demo)
        $subscription = Subscription::whereIn('type', ['demo', 'paid'])
            ->where('status', 'active')
            ->orderBy('end_date', 'desc')
            ->first();

        // Auto-expire if end_date passed
        if ($subscription && Carbon::parse($subscription->end_date)->isPast()) {
            $subscription->update(['status' => 'expired']);
            $subscription = null;
        }

        // Redirect if no active subscription
        if (!$subscription) {
            return redirect()->route('subscription.payment')
                ->with('error', 'The system subscription/demo is inactive. Please activate.');
        }

        return $next($request);
    }
}

