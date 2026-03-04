@component('mail::message')
# Hello {{ $mailData['name'] ?? 'Member' }},

Good news! All members have completed their contributions for **{{ $mailData['monthYear'] ?? 'this month' }}**.  

Here’s a summary of the contributions:

**Contribution Summary:**  
- **Total Contributed This Month:** KES {{ number_format($mailData['totalCollected'] ?? 0, 2) }}  
- **Total Contributed Since First Month:** KES {{ number_format($mailData['totalAllTime'] ?? 0, 2) }}  
- **Total Penalties Collected:** KES {{ number_format($mailData['totalPenalties'] ?? 0, 2) }}

We wish you a productive and happy month ahead! Please be ready for next month's contribution.  

@component('mail::button', ['url' => $mailData['dashboardUrl'] ?? url('/')])
View Contributions
@endcomponent

Best regards,<br>
**{{ env('WEBSITE_NAME') ?? 'Our Team' }}**

---

<sub>This software is powered by <a href="https://jminnovatechsolution.co.ke/" target="_blank">JM Innovatech Solution</a>.</sub>
@endcomponent