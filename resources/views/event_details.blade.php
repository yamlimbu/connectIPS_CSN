@extends('layouts.app')
@section('page-content')
    <section class="slider_section ">
        <div class="container">
            <div class="row">
                @foreach ($data as $index => $event)
                    @php
                        $start_date = \Carbon\Carbon::parse($event['start_date']);
                        $end_date = \Carbon\Carbon::parse($event['end_date']);
                    @endphp


                    @if ($event_id == $event['id'])
                        <div class="row">
                            <div class="col-md-5">
                                <div class="img-box">
                                    <img width="700"
                                        src="{{ file_exists(public_path($event['banner'])) ? asset($event['banner']) : asset('images/slider-img.jpg') }}"
                                        alt="{{ $event['name'] }}">
                                </div>
                            </div>


                            <div class="col-md-7">
                                <div class="detail-box">

                                    <h3>
                                        {{ $event['name'] }}
                                    </h3>


                                    <p>
                                        <Strong>Start Date:</Strong>{{ $start_date->format('F j, Y') }}
                                    </p>

                                    <p>
                                        <Strong>End Date:</Strong> {{ $end_date->format('F j, Y') }}
                                    </p>
                                    <p>
                                        <Strong>Venue:</Strong> {{ $event['location'] }}
                                    </p>


                                    <p>
                                        {{ $event['information'] }}
                                    </p>
                                    <a href="{{ route('event.register', $event['id']) }}">
                                        Register Now
                                    </a>

                                </div>
                            </div>

                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- end slider section -->
@endsection
