<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            box-sizing: border-box;
        }
        h1 {
            font-size: 24px;
            color: #333;
        }
        .panel {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .panel img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">

        <p>Dear {{ $registration->full_name }},</p>
@if(count($eventCategoryTickets) > 1)

<p>We are pleased to confirm your registration for the <strong>{{ $eventCategoryTickets[0]->title }}</strong> and <strong>XXII International Congress on Management of Cardiovascular Disease</strong>. </p>
<p>We are also delighted to inform you that you are eligible to attend the {{ $eventCategoryTickets[0]->title }}, which will take place on the 24th of October 2024 at Hotel Radisson, Lazimpat. A lunch session will be included as part of the Session.

</p>
We are also delighted to inform you that you are eligible to attend the Congress, which will take place on the 25th and 26th of October 2024 at Hotel Yak and Yeti, Kathmandu.
    </p>
    <p>
As a registered participant, you will be eligible to receive the registration kit, and enjoy Lunch on 25th & 26th of October at Hitel Yak & Yeti and Gala Dinner on 25th of October at Hotel Radisson, Lazimpat.

</p>
<p>
Details of the timing will be shared in the registration kit.
</p>


@else
        <p>We are pleased to confirm your registration for the <strong>@if($eventCategoryTickets[0]->event_category_id == 1){{ $eventCategoryTickets[0]->title }}@else {{ $registration->event->name}} @endif</strong>.
@if($eventCategoryTickets[0]->event_category_id == 1)
<p>We are pleased to confirm your registration for the {{ $eventCategoryTickets[0]->title }}. We are also delighted to inform you that you are eligible to attend the Nursing Symposium 2024, which will take place on the 24th of October 2024 at {{ $eventCategoryTickets[0]->location }}. A lunch session will be included as part of the @if($eventCategoryTickets[0]->id == 1)symposium @else Session @endif.</p>

<p>Should you require any further information or have any questions, please feel free to reach out.</p>
@else
<p>We are pleased to confirm your registration for the XXII International Congress on Management of Cardiovascular Disease. We are also delighted to inform you that you are eligible to attend the Congress, which will take place on the 25th and 26th of October 2024 at Hotel Yak and Yeti, Kathmandu.</p>

<p>As a registered participant, you will be eligible to receive the registration kit, and enjoy Lunch on 25th & 26th of October at Hitel Yak & Yeti and Gala Dinner on 25th of October at Hotel Radisson, Lazimpat.</p>

<p>Details of the timing will be shared in the registration kit.</p>
@endif
@endif
        <p>We look forward to your active participation.</p>

        <p><strong>Event Token:</strong> {{ $registration->event_token }}</p>

        <div class="panel">
            <p>Scan the QR code below to get your event token:</p>
            <img src="{{ asset('storage/' . $qrToken) }}" alt="QR Code">
        </div>

         <p>Thanks,<br>Cardiac Society of Nepal</p>
    </div>
</body>
</html>
