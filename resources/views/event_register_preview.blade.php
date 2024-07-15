@extends('layouts.app')
@section('page-content')
<section class="slider_section ">
    <div class="container">
        <div class="row">
            <form action="https://uat.connectips.com/connectipswebgw/loginpage" method="post">
                <label for="MERCHANTID">MERCHANT ID</label>
                <input type="text" name="MERCHANTID" id="MERCHANTID" value="{{ config('app.merchantid') }}" />

                <label for="APPID">APP ID</label>
                <input type="text" name="APPID" id="APPID" value="{{ config('app.appid') }}" />

                <label for="APPNAME">APP NAME</label>
                <input type="text" name="APPNAME" id="APPNAME" value="Cardiac Society" />

                <label for="TXNID">TXN ID</label>
                <input type="text" name="TXNID" id="TXNID" value="txn-333333" />

                <label for="TXNDATE">TXN DATE</label>
                <input type="text" name="TXNDATE" id="TXNDATE" value="15-03-2022" />

                <label for="TXNCRNCY">TXN CRNCY</label>
                <input type="text" name="TXNCRNCY" id="TXNCRNCY" value="NPR" />

                <label for="TXNAMT">TXN AMT</label>
                <input type="text" name="TXNAMT" id="TXNAMT" value="500" />

                <label for="REFERENCEID">REFERENCE ID</label>
                <input type="text" name="REFERENCEID" id="REFERENCEID" value="REF-001" />

                <label for="REMARKS">REMARKS</label>
                <input type="text" name="REMARKS" id="REMARKS" value="RMKS-001" />

                <label for="PARTICULARS">PARTICULARS</label>
                <input type="text" name="PARTICULARS" id="PARTICULARS" value="PART-001" />

                <label for="TOKEN">TOKEN</label>
                <input type="text" name="TOKEN" id="TOKEN" value="{{$data['token']}}" />

                <br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</section>

<!-- end slider section -->
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');

        form.addEventListener('submit', function(event) {
            return false;
            event.preventDefault(); // Prevent default form submission

            // Gather form data
            const formData = new FormData(form);
            const payload = {};

            formData.forEach((value, key) => {
                payload[key] = value;
            });

            // Log the payload to the console
            console.log('Form Payload:', payload);

            // Optionally, manually submit the form
            // You can use fetch() to send data to the server
            fetch('https://baseurl/connectipswebgw/loginpage', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF Token if required
                    },
                    body: new URLSearchParams(payload).toString() // Convert formData to URL-encoded string
                })
                .then(response => response.json()) // Expect JSON response
                .then(data => console.log('Response:', data)) // Log response
                .catch(error => console.error('Error:', error)); // Log error
        });
    });
</script>
@endpush
