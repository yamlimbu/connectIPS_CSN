<!-- resources/views/emails/event_code.blade.php -->

@component('mail::message')
# Event Registration

Thank you for registering for the event. Please find your event token and QR code below.

**Event Token:** {{ $registration->event_token }}

@component('mail::panel')
<img src="data:image/png;base64, {{ $qrCodeBase64 }}" alt="QR Code">
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
