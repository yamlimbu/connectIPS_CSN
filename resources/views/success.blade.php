@extends('layouts.app')

@section('page-content')
    <section class="book_section layout_padding">
        <div class="container text-center">
            @if (session('success'))
                <div class="alert alert-success success-message">
                    <div class="icon-container">
                        <img src="{{ asset('images/success.png') }}" alt="Success Icon" class="success-icon">
                    </div>
                    <h2 class="success-title">Registration success!! You have successfully completed the registration
                        process.</h2>
                    <p class="success-description">{{ session('success') }}</p>
                    <a href="{{ url('/') }}" class="btn btn-primary mt-4">Go to Homepage</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Custom Styles -->
    <style>
        .success-message {
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        .success-icon {
            width: 100px;
            margin-bottom: 20px;
        }

        .success-title {
            font-size: 2.5rem;
            color: #155724;
            margin-bottom: 10px;
        }

        .success-description {
            font-size: 1.2rem;
            color: #155724;
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
