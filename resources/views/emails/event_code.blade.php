@component('mail::message')
# Event Registration

Thank you for registering for the event. Please find your event token and QR codes below.

**Event Token:** {{ $registration->event_token }}

@component('mail::panel')
Scan the QR code below to get your event token:
<br>
<img src="{{ asset('storage/' . $qrToken) }}" alt="QR Code" style="max-width: 100%; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';">
@endcomponent

@component('mail::panel')
Scan the QR code below to view your gatepass details:
<br>
<img src="{{ asset('storage/' . $qrGatePass) }}" alt="Details QR Code" style="max-width: 100%; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';">
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
