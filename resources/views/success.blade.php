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
            <p class="success-description mb-2 mb-md-4">You have successfully completed the registration process.</p>
            <a href="{{ url('/') }}" class="btn btn-success btn-sm">Go to Homepage</a>
        </div>
        @endif
    </div>
</section>


@endsection