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
                                                <img src="{{ file_exists(public_path($event['banner'])) ? asset($event['banner']) : asset('images/slider-img.jpg') }}"
                                                    alt="{{ $event['name'] }}">
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
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="img-box">
                        <img width="700" src="images/slider-img.jpg"
                            alt="XII Internation Congress On Management Of Cardiovascular Disease">
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="detail-box">
                        <h3>
                            XII Internation Congress On Management Of Cardiovascular Disease
                        </h3>
                        <p>
                            <strong>Start Date:</strong>
                            July 3, 2024
                        </p>
                        <p>
                            <strong>End Date:</strong>
                            August 9, 2024
                        </p>
                        <p>
                            <strong>Venue:</strong>
                            Kathmandu nepal
                        </p>
                        <p>
                            When looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal
                            distribution of letters, as opposed to
                        </p>
                        <a href="{{ route('event.register', 1) }}">
                            Register Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="book_section layout_padding">
        <div class="container">
            <h4>
                <span> Payment Options </span>
            </h4>
            <div class="row">

                <div class="col-md-6">
                    <div class="img-box pull-right">
                        <img src="{{ asset('images/connectips.png') }}" alt="" width="150">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="img-box">
                        <img src="{{ asset('images/fonepay.png') }}" alt="" width="150">
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
