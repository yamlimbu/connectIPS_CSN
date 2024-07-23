@component('mail::message')
# Event Registration

Thank you for registering for the event. Please find your event token and QR code attached.

**Event Token:** {{ $registration->event_token }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
