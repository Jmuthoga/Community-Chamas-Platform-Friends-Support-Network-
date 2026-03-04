@component('mail::message')
# Hello {{ $mailData['name'] ?? 'Member' }},

Thank you for your contribution for **{{ $mailData['monthYear'] ?? 'this month' }}**.  

Here’s a summary of the current status:

**Contribution Summary:**  
- **Total Members:** {{ $mailData['totalMembers'] ?? 0 }}  
- **Members Contributed:** {{ $mailData['contributedCount'] ?? 0 }}  
- **Members Remaining:** {{ $mailData['remainingCount'] ?? 0 }}  
- **Total Collected This Month:** KES {{ number_format($mailData['totalCollected'] ?? 0, 2) }}  
- **Remaining Balance:** KES {{ number_format($mailData['remainingBalance'] ?? 0, 2) }}

@component('mail::button', ['url' => $mailData['dashboardUrl'] ?? url('/')])
View Contributions
@endcomponent

We appreciate your continued support and commitment to our community.  

Best regards,<br>
**{{ env('WEBSITE_NAME') ?? 'Our Team' }}**

---

<sub>This software is powered by <a href="https://jminnovatechsolution.co.ke/" target="_blank">JM Innovatech Solution</a>.</sub>
@endcomponent