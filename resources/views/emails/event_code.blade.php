@component('mail::message')
# Event Registration

Thank you for registering for the event. Please find your event token and QR code below.

**Event Token:** {{ $registration->event_token }}

@component('mail::panel')
<a href="{{ asset('storage/' . $qrToken) }}">def</a>
<a href="{{ url('/qr-code/' . $qrToken) }}">abc</a>
<img src="{{ asset('storage/' . $qrToken) }}" alt="QR Code" style="width:150px; height:150px;">
<img src="{{ url('/qr-code/' . $qrToken) }}" alt="QR Code" style="width:150px; height:150px;">

@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
