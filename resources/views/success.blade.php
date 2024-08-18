@extends('layouts.app')
@section('page-content')
<section class="message-section fixed-vh d-flex align-items-center">
    <div class="container text-center">
        @if (session('success'))
        <div class="alert success-message">
            <div class="icon-container mb-4">
                <img src="{{ asset('images/success.png') }}" alt="Success Icon" class="success-icon">
            </div>
            <h2 class="success-title">Registration success!! </h2>
            <p class="success-description mb-2 mb-md-2">You have successfully completed the registration process.</p>
            <p class="success-description mb-2 mb-md-2">Please find the details of the events.</p>

        </div>
        @endif


        <div class="card">
            <div class="card-header fw-bold text-uppercase p-2 p-md-3">Event Details</div>
            <div class="card-body p-2 p-md-3">
                <div class="row mb-2 mb-md-4">
                    <p class="banner-title small-text fw-bold mb-1 mb-md-2">{!! $event->banner_text !!}</p>

                    <h3 class="banner-title main">{{$event->name}}</h3>

                    <p class="mb-1">
                        <strong>Date:</strong>
                        {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}
                        - {{ \Carbon\Carbon::parse($event->end_date)->format('d F Y') }}
                    </p>
                    <p class="mb-2 mb-md-4">
                        <strong>Venue:</strong>
                        {{$event->location}}
                    </p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-bold text-uppercase p-2 p-md-3">Your Details</div>
            <div class="card-body p-2 p-md-3">
                <div class="row mb-2 mb-md-4">
                    <div class="form-group col-md-4">
                        <label class="form-label" for="nmc_registration_number"><strong>NMC Number</strong> </label>:-
                        {{$registration->nmc_registration_number}}
                    </div>

                    <div class="form-group col-md-4">
                        <label class="form-label" for="full_name"><strong>Full Name</strong> </label>:-
                        {{$registration->full_name}}
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label" for="address"><strong>Address</strong> </label>:-
                        {{$registration->address}}
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label" for="degree"><strong>Degree</strong> </label>:-
                        {{$registration->degree}}
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label" for="nmc_registration_number"><strong>Email Address</strong> </label>:-
                        {{$registration->email_address}}
                    </div>
                    <div class="form-group col-md-4">
                        <label class="form-label" for="nmc_registration_number"><strong>Phone Number</strong> </label>:-
                        {{$registration->phone_number}}
                    </div>
                </div>
                @foreach($ticketDerails as $ticket)
        @php
        @endphp
                <div class="form-row mb-4">
                    <div class="col-md-12">
                        <div class="heading mb-2">{{$ticket->eventcategoryticket->eventcategory->title}}</div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ticket</th>
                                        <th>Early Bird (Till 20th Oct 2024)</th>
                                        <th>Late & On-Site</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="50%">{{ $ticket->eventcategoryticket->title }}</td>
                                        <td>
                                            {{ $ticket->eventCategoryTicketPriceType->name == 'Early Bird' ? '✔' : '' }}
                                        </td>
                                        <td>
                                            {{ $ticket->eventCategoryTicketPriceType->name != 'Early Bird' ? '✔' : '' }}

                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                @endforeach
            </div>

        </div>
        <div class="card">
    <div class="card-header fw-bold text-uppercase p-2 p-md-3">
        Please find QR for your entrance
    </div>
    <div class="card-body p-2 p-md-3">
        <div class="row mb-2 mb-md-4">
            <div class="col text-center">
                <img src="{{ asset('storage/qrcodes/' . $registration->event_token . '.png') }}" style="width: 150px; height: auto; max-width: 100%;" />
            </div>
        </div>
    </div>
</div>

        @if (!$isMobile)<div class="mt-4 mb-4"><a href="{{ url('/') }}" class="btn btn-success btn-sm">Go to Homepage</a></div>@endif

    </div>

    </div>
</section>
@endsection
