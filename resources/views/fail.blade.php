@extends('layouts.app')

@section('page-content')
    <section class="book_section layout_padding">
        <div class="container text-center">
            @if (session('error'))
                <div class="alert alert-danger error-message">
                    <div class="icon-container">
                        <img src="{{ asset('images/error.png') }}" alt="Error Icon" class="error-icon">
                    </div>
                    <h2 class="error-title">Registration Unsuccessful! We regret to inform you that your registration was not
                        completed successfully.</h2>
                    <p class="error-description">{{ session('error') }}</p>
                    <a href="{{ url('/') }}" class="btn btn-primary mt-4">Go to Homepage</a>
                    <a href="{{ url('/register/1') }}" class="btn btn-secondary mt-4">Try Again</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Custom Styles -->
    <style>
        .error-message {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        .error-icon {
            width: 100px;
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 2.5rem;
            color: #721c24;
            margin-bottom: 10px;
        }

        .error-description {
            font-size: 1.2rem;
            color: #721c24;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
@endsection
