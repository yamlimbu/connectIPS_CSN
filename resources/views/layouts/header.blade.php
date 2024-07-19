<header class="header_section">
    {{-- <div class="header_top">
          <div class="container">
              <div class="contact_nav">
                  <a href="">
                      <i class="fa fa-phone" aria-hidden="true"></i>
                      <span>
                          Call : +01 123455678990
                      </span>
                  </a>
                  <a href="">
                      <i class="fa fa-envelope" aria-hidden="true"></i>
                      <span>
                          Email : demo@gmail.com
                      </span>
                  </a>
                  <a href="">
                      <i class="fa fa-map-marker" aria-hidden="true"></i>
                      <span>
                          Location
                      </span>
                  </a>
              </div>
          </div>
      </div> --}}
    <div class="header-top text-center text-white">
        <div class="container d-flex justify-content-center align-items-center gap-3">
            <div>Join us for Management Of Cardiovascular Disease 2024, October 25-26 in Kathmandu, Nepal.</div>
            <a href="{{ route('event.register', 1) }}" class="btn btn-danger hover">Join Us</a>
        </div>
    </div>
    <div class="header_bottom">
        <div class="container">
            <nav class="navbar d-flex justify-content-between ">
                <a class="navbar-brand d-flex gap-2 align-items-center" href="{{ URL::to('/') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="">
                    <div class="brand-title">
                        <span>Cardiac Society of Nepal</span> <br>
                        <span class="text-danger">International Conference</span>
                    </div>
                </a>

                <div class="quote_btn-container">


                    @if (URL::current() == route('event.register', 1))
                    <a href="{{ URL::to('/') }}" class="d-none">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <span>Home</span>
                    </a>
                    @else
                    <a href="{{ route('event.register', 1) }}" class="register text-dark d-none d-md-block">
                        Register Now
                    </a>
                    @endif



                </div>
            </nav>
        </div>
    </div>
</header>