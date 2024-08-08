<header class="header_section">
    <div class="header-top text-center text-white">
        <div class="container d-flex justify-content-center align-items-center gap-2">
            @if (URL::current() !== route('event.register', 1) && URL::current() !== route('preview'))
            <div>Join us for Management Of Cardiovascular Disease 2024, October 25-26 in Kathmandu, Nepal.</div>
            <a href="{{ route('event.register', 1) }}" class="btn btn-navy hover">Join Us</a>
            @else
            <div>XXII International Congress On
                Management Of Cardiovascular Disease 2024, October 25-26 in Kathmandu, Nepal.</div>
            @endif

        </div>
    </div>
    <div class="header_bottom">
        <div class="container">
            <nav class="navbar d-flex justify-content-between ">
                <a class="navbar-brand d-flex gap-2 align-items-center" href="{{ URL::to('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="">
                    <div class="brand-title">
                        <span>Cardiac Society of Nepal</span> <br>
                        <span class="text-blue">International Conference</span>
                    </div>
                </a>

                <div class="quote_btn-container">


                    @if (URL::current() == route('event.register', 1) || Route::is('preview'))
                    <a href="{{ URL::to('/') }}" >
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <span>Home</span>
                    </a>

                    @else
                    <a href="{{ route('event.register', 1) }}" class="btn btn-gray d-none d-md-block fw-bold">
                        Register Now
                    </a>
                    @endif



                </div>
            </nav>
        </div>
    </div>
</header>
