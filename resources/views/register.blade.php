@extends('layouts.app')
@section('page-content')
<section class="register-section py-4">
    <div class="container">

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
        @endif

        <div class="bg-light mb-5">
            <form method="POST" action="{{ route('event_register') }}">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event_id }}">
                <div class="card">
                    <div class="card-header fw-bold text-uppercase p-2 p-md-3">Register</div>
                    <div class="card-body p-2 p-md-3">
                        <div class="row mb-4">
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="nmc_registration_number">NMC Number</label>
                                <input type="text" class="form-control " id="nmc_registration_number" name="nmc_registration_number" placeholder="" value="{{ old('nmc_registration_number', session('data.nmc_registration_number')) }}">
                                @if ($errors->has('nmc_registration_number'))
                                <span class="text-danger">{{ $errors->first('nmc_registration_number') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="" value="{{ old('full_name', session('data.full_name')) }}">
                                @if ($errors->has('full_name'))
                                <span class="text-danger">{{ $errors->first('full_name') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="" value="{{ old('address', session('data.address')) }}">
                                @if ($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="degree">Degree</label>
                                <input type="text" class="form-control" id="degree" name="degree" placeholder="" value="{{ old('degree', session('data.degree')) }}">
                                @if ($errors->has('degree'))
                                <span class="text-danger">{{ $errors->first('degree') }}</span>
                                @endif
                            </div>
                            <input type="hidden" class="form-control" id="gender" name="gender" placeholder="" value="{{ old('gender', session('data.gender')) }}">

                            <!-- <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="{{ old('first_name', session('data.first_name')) }}">
                                @if ($errors->has('first_name'))
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="middle_name">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="" value="{{ old('middle_name', session('data.middle_name')) }}">
                                @if ($errors->has('middle_name'))
                                <span class="text-danger">{{ $errors->first('middle_name') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" value="{{ old('last_name', session('data.last_name')) }}">
                                @if ($errors->has('last_name'))
                                <span class="text-danger">{{ $errors->first('last_name', session('data.last_name')) }}</span>
                                @endif
                            </div> -->
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="email_address">Email Address</label>
                                <input type="text" class="form-control" id="email_address" name="email_address" placeholder="" value="{{ old('email_address', session('data.email_address')) }}">
                                @if ($errors->has('email_address'))
                                <span class="text-danger">{{ $errors->first('email_address') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="" value="{{ old('phone_number',session('data.phone_number')) }}">
                                @if ($errors->has('phone_number'))
                                <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            @foreach ($data->eventCategories as $category)
                            <div class="mb-2 mb-md-4">
                                <div class="heading mb-2">{{ $category->title }}</div>
                                <span class="text-danger">{{ $errors->first('event_category_ticket_prices_ids') }}</span>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Ticket</th>
                                                <th>Early Bird (Till 20th Oct 2024)</th>
                                                <th>Late & On-Site</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($category->tickets as $ticket)
                                            @php
                                            $earlyBirdPrice = $ticket->prices->firstWhere(
                                            'event_category_ticket_name',
                                            'Early Bird',
                                            );
                                            $lateOnsitePrice = $ticket->prices->firstWhere(
                                            'event_category_ticket_name',
                                            'Late & On-site',
                                            );
                                            $oldPriceId = old("event_category_ticket_prices_ids.{$category->id}", session("data.event_category_ticket_prices_ids.{$category->id}"));
                                            $cutoffDate = \Carbon\Carbon::create(2024, 10, 19);
                                            $currentDate = \Carbon\Carbon::now();
                                            $isDisabled = $currentDate->lessThan($cutoffDate);

                                            @endphp
                                            <tr>
                                                <td width="50%">
                                                    {{ $ticket->title }}
                                                </td>
                                                <td>
                                                    @if ($earlyBirdPrice)
                                                    <div class="form-group col-lg-12">
                                                        <input class="form-check-input" type="radio" name="event_category_ticket_prices_ids[{{ $category->id }}]" id="ticket_{{ $ticket->id }}_early_bird" value="{{ $earlyBirdPrice->id }}" {{ $oldPriceId == $earlyBirdPrice->id ? 'checked' : '' }}>
                                                        {{ $earlyBirdPrice->price }}
                                                    </div>
                                                    @else
                                                    <div class="form-group col-lg-12">
                                                        N/A
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($lateOnsitePrice)
                                                    <div class="form-group col-lg-12"  style="color: lightgray;">
                                                        <input class="form-check-input" type="radio" name="event_category_ticket_prices_ids[{{ $category->id }}]" id="ticket_{{ $ticket->id }}_late_onsite" value="{{ $lateOnsitePrice->id }}" {{ $oldPriceId == $lateOnsitePrice->id ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }}>
                                                        {{ $lateOnsitePrice->price }}
                                                    </div>
                                                    @else
                                                    <div class="form-group col-lg-12">
                                                        N/A
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="payment_receipt" id="payment_receipt" value="434RR">
                        <input type="hidden" name="payment_status" id="payment_status" value="pending">
                        <input type="hidden" name="total_amount" id="total_amount" value="300">
                        <input type="hidden" name="transaction_id" id="transaction_id" value="123">
                    </div>
                    <div class="card-footer text-end border-0">
                        <button type="submit" name="registration_form" value="submitted" class="btn btn-primary btn-sm float-right">Register</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Uncheck all other radios in the same category
                document.querySelectorAll(
                    `input[name="event_category_ticket_prices_ids[${radio.getAttribute('name').match(/\d+/)[0]}]"]`
                ).forEach(function(otherRadio) {
                    if (otherRadio !== radio) {
                        otherRadio.checked = false;
                    }
                });
            });
        });
    });
</script>
<script>
        $(document).ready(function() {
            let debounceTimeout;

            $('#nmc_registration_number').on('keyup', function() {
                clearTimeout(debounceTimeout);

                let query = $(this).val();
                if (query.length > 0) {
                    $('#full_name, #address, #degree, #gender').addClass('loading');
                    debounceTimeout = setTimeout(function() {
                        $.ajax({
                            url: "{{ route('search.nmc') }}",
                            type: "GET",
                            data: { query: query },
                            dataType: 'json',
                            success: function(response) {
                                $('#full_name, #address, #degree, #gender').removeClass('loading'); // Remove 'loading' class if query length is less than 2

                                if (response.length > 0) {

                                    // Assuming only one result for simplicity
                                    let item = response[0];
                                    $('#full_name').val(item.full_name);
                                    $('#address').val(item.address);
                                    $('#degree').val(item.degree);
                                    $('#gender').val(item.gender);
                                } else {
                                    // Clear the fields if no result found
                                    $('#full_name').val('');
                                    $('#address').val('');
                                    $('#degree').val('');
                                    $('#gender').val('');
                                }
                            },
                            error: function() {
                                $('#full_name, #address, #degree, #gender').removeClass('loading'); // Remove 'loading' class if query length is less than 2

                                // Handle the error
                                console.log('Error retrieving results.');
                            }
                        });
                    }, 300); // Delay in milliseconds
                } else {
                    $('#full_name, #address, #degree, #gender').removeClass('loading'); // Remove 'loading' class if query length is less than 2

                    // Clear the fields if query length is less than 2
                    $('#full_name').val('');
                    $('#address').val('');
                    $('#degree').val('');
                    $('#gender').val('');
                }
            });
        });
    </script>
@endpush
