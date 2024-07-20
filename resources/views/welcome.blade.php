@extends('layouts.app')
@section('page-content')
@php
use Illuminate\Support\Str;
@endphp
<div class="hero_area d-none">
    <section class="slider_section ">
        <div class="dot_design">
            <img src="images/dots.png" alt="">
        </div>
        <div id="customCarousel1" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @isset($events)
                @foreach ($events as $index => $event)
                @php
                $nameParts = explode(' ', $event['name']);
                $lastNamePart = array_pop($nameParts);
                $firstNamePart = implode(' ', $nameParts);
                @endphp
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-box">
                                    <h1>
                                        {{ Str::limit($firstNamePart, 30) }} <br>
                                        <span>
                                            {{ $lastNamePart }}
                                        </span>
                                    </h1>
                                    <p>
                                        {{ Str::limit($event['information'], 200) }}
                                    </p>
                                    <a href="{{ route('event.register', $event['id']) }}">
                                        Register Now
                                    </a>
                                    <a href="{{ route('event.details', $event['id']) }}" style="background:green;">
                                        View Details
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="img-box">
                                    <img src="{{ file_exists(public_path($event['banner'])) ? asset($event['banner']) : asset('images/slider-img.jpg') }}" alt="{{ $event['name'] }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endisset

            </div>
            <div class="carousel_btn-box">
                <a class="carousel-control-prev" href="#customCarousel1" role="button" data-slide="prev">
                    <img src="images/prev.png" alt="">
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#customCarousel1" role="button" data-slide="next">
                    <img src="images/next.png" alt="">
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </section>
</div>
<!-- end slider section -->



<section class="slider_section ">
    <div class="container h-100">
        <div class="row h-100 align-content-center justify-content-between">
            <div class="col-md-7">
                <div class="detail-box py-4 py-md-0 mb-2 mb-md-0">
                    <p class="banner-title small-text fw-bold mb-1 mb-md-2">Conquering heart disease in the himalayan
                        region</p>

                    <h3 class="banner-title main">XXII International Congress On <br> Management Of Cardiovascular
                        Disease</h3>

                    <p class="mb-1">
                        <strong>Date:</strong>
                        25 - 26 October 2024
                    </p>
                    <p class="mb-2 mb-md-4">
                        <strong>Venue:</strong>
                        Kathmandu, Nepal
                    </p>
                    <div class="counter">
                        <div id="countdown" class="d-flex gap-2">
                            <div class="btn countdown-div">
                                <span class="days">00</span>
                                <span class="days_text">Days</span>
                            </div>
                            <div class="btn countdown-div">
                                <span class="hours">00</span>
                                <span class="hours_text">Hours</span>
                            </div>
                            <div class="btn countdown-div">
                                <span class="minutes">00</span>
                                <span class="minutes_text">Minutes</span>
                            </div>
                            <div class="btn countdown-div">
                                <span class="seconds">00</span>
                                <span class="seconds_text">Seconds</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4 align-self-center pb-4 pb-md-0">
                <div class="card">
                    <div class="card-body reg-fee">
                        <div class="overlay-img"></div>
                        <div class="fee-content position-absolute top-50 start-50 translate-middle">
                            <div class="banner-title fw-bold text-uppercase mb-2 mb-md-3">Registration Fee</div>
                            <div class="mb-1 mb-md-2">
                                <span class="fw-bold">CSN Members:-</span>
                                <span>Rs 10000</span>
                            </div>
                            <div class="mb-1 mb-md-2">
                                <span class="fw-bold">Delegates:-</span>
                                <span>Rs 12000</span>
                            </div>

                            <div class="mb-1 mb-md-2">
                                <span class="fw-bold">Residents/Fellow:-</span>
                                <span>Rs 5000</span>
                            </div>
                            <div class="mb-3 mb-md-2">
                                <span class="fw-bold">Cardiovascular Specialists:-</span>
                                <span>Rs 5000</span>
                            </div>
                            <a href="{{ route('event.register', 1) }}" class="btn btn-navy text-danger d-md-none">Register Now</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-4 py-md-5">
    <div class="container">
        <div class="payment-section">
            <div class="mb-4 text-center">
                <h3 class="mb-0 mb-md-1">Payment Options </h3>
                <div class="line"></div>
            </div>
            <div class="card payment border-0">
                <div class="card-body p-0">
                    <div class="payment-methods d-flex gap-2 justify-content-center w-100">
                        <div class="image-box">
                            <img src="{{ asset('images/connectips.png') }}" alt="Connect IPS">
                        </div>
                        <div class="image-box">
                            <img src="{{ asset('images/fonepay.png') }}" alt="Fonepay">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection

@push('scripts')
<script class="source" type="text/javascript">
    var nextyear = '10/25/2024 11:00:01';
    var now = new Date();
    var day = now.getDate();
    var month = now.getMonth() + 1;
    var year = now.getFullYear() + 1;

    // var nextyear = '12/02/2022 01:01:01';

    $('#countdown').countdown({
        date: nextyear, // TODO Date format: 07/27/2017 17:00:00
        offset: +2, // TODO Your Timezone Offset
        day: 'Day',
        days: 'Days',
        hideOnComplete: true
    }, function(container) {
        alert('Done!');
    });
</script>
@endpush