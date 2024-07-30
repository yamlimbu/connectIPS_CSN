@extends('layouts.app')

@section('page-content')
<section class="message-section fixed-vh d-flex align-items-center">
    <div class="container text-center">
        @if (session('error'))
        <div class="alert error-message">
            <div class="icon-container mb-4">
                <img src="{{ asset('images/error.png') }}" alt="Error Icon" class="error-icon">
            </div>
            <h2 class="error-title">Registration Unsuccessful!</h2>
            <p class="error-description mb-2 mb-md-4">We regret to inform you that your registration was not completed
                successfully.</p>
                @if (!$isMobile)<a href="{{ url('/') }}" class="btn btn-success">Go to Homepage</a>
            <a href="{{ url('/register/1') }}" class="btn btn-secondary">Try Again</a>@endif
        </div>
        @endif
    </div>
</section>

@endsection
