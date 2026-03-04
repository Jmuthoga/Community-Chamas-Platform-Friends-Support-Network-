@component('mail::message')
# Hello {{ $mailData['name'] ?? 'Member' }},

You have a **new notification** from {{ env('WEBSITE_NAME') ?? 'our system' }}.

**Notification Title:**  
{{ $mailData['title'] ?? 'No Title' }}

**Message:**  
{{ $mailData['message'] ?? 'No details provided.' }}

@component('mail::button', ['url' => $mailData['dashboardUrl'] ?? url('/')])
View Your Dashboard
@endcomponent

Thank you for staying connected with us.  

Best regards,<br>
**{{ env('WEBSITE_NAME') ?? 'Our Team' }}**

---

<sub>This software is powered by <a href="https://jminnovatechsolution.co.ke/" target="_blank">JM Innovatech Solution</a>.</sub>
@endcomponent