@component('mail::message')
# Event Registration

Thank you for registering for the event. Please find your event token and QR code below.

**Event Token:** {{ $registration->event_token }}

@component('mail::panel')
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
