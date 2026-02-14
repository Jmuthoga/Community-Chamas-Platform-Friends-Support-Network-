@component('mail::message')
# Hello {{ $mailData['name'] }},

This contribution summary has been successfully generated and recorded following your recent payment for **{{ $mailData['monthYear'] }}**.

### Contribution Summary
- **Total Contributed This Month:** KES {{ number_format($mailData['totalCollected'], 2) }}
- **Total Contributed Since Joining:** KES {{ number_format($mailData['totalAllTime'], 2) }}
- **Total Penalties Collected:** KES {{ number_format($mailData['totalPenalties'], 2) }}

Thank you for your commitment and continued support. We wish you a productive and successful month ahead. Kindly remember to prepare for your next monthly contribution.

@component('mail::button', ['url' => $mailData['dashboardUrl']])
View Contribution Details
@endcomponent

Thanks,<br>
{{ env('WEBSITE_NAME') }}
@endcomponent
