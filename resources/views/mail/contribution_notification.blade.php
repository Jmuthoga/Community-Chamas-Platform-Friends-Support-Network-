@component('mail::message')
# Hello {{ $mailData['name'] }},

You have just made your contribution for {{ $mailData['monthYear'] }}.

**Current Summary:**
- Total Members: {{ $mailData['totalMembers'] }}
- Members Contributed: {{ $mailData['contributedCount'] }}
- Members Remaining: {{ $mailData['remainingCount'] }}
- Total Collected This Month: KES {{ number_format($mailData['totalCollected'], 2) }}
- Remaining Balance: KES {{ number_format($mailData['remainingBalance'], 2) }}

@component('mail::button', ['url' => $mailData['dashboardUrl']])
View Contributions
@endcomponent

Thanks,<br>
{{ env('WEBSITE_NAME') }}
@endcomponent
