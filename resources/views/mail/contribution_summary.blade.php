@component('mail::message')
# Hello {{ $mailData['name'] }},

All members have completed their contributions for {{ $mailData['monthYear'] }}.

**Contribution Summary:**
- Total Contributed This Month: KES {{ number_format($mailData['totalCollected'], 2) }}
- Total Contributed Since First Month: KES {{ number_format($mailData['totalAllTime'], 2) }}
- Total Penalties Collected: KES {{ number_format($mailData['totalPenalties'], 2) }}

We wish you a happy month! Please be ready for next month's contribution.

@component('mail::button', ['url' => $mailData['dashboardUrl']])
View Contributions
@endcomponent

Thanks,<br>
{{ env('WEBSITE_NAME') }}
@endcomponent
