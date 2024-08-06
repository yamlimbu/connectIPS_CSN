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
                                <div class="input-group">

                                    <input type="text" class="form-control " id="nmc_registration_number" name="nmc_registration_number" placeholder="" value="{{ old('nmc_registration_number', $eventRegistrationHold['nmc_registration_number'] ?? '') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" type="button" id="search-nmc">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                @if ($errors->has('nmc_registration_number'))
                                <span class="text-danger">{{ $errors->first('nmc_registration_number') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="" value="{{ old('full_name', $eventRegistrationHold['full_name'] ?? '') }}">
                                @if ($errors->has('full_name'))
                                <span class="text-danger">{{ $errors->first('full_name') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="" value="{{ old('address', $eventRegistrationHold['address'] ?? '') }}">
                                @if ($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="degree">Degree</label>
                                <input type="text" class="form-control" id="degree" name="degree" placeholder="" value="{{ old('degree', $eventRegistrationHold['degree'] ?? '') }}">
                                @if ($errors->has('degree'))
                                <span class="text-danger">{{ $errors->first('degree') }}</span>
                                @endif
                            </div>
                            <input type="hidden" class="form-control" id="gender" name="gender" placeholder="" value="{{ old('gender', $eventRegistrationHold['gender'] ?? '') }}">

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
                                <input type="text" class="form-control" id="email_address" name="email_address" placeholder="" value="{{ old('email_address', $eventRegistrationHold['email_address'] ?? '') }}">
                                @if ($errors->has('email_address'))
                                <span class="text-danger">{{ $errors->first('email_address') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label class="form-label" for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="" value="{{ old('phone_number', $eventRegistrationHold['phone_number'] ?? '') }}">
                                @if ($errors->has('phone_number'))
                                <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            @foreach ($data->eventCategories as $category)
                            <div class="mb-2 mb-md-4">
                                <div class="heading mb-2">{{ $category->title }}</div>
                                <button type="button" onclick="deselectRadioButton({{ $category->id }})" class="pre-congress">Deselect</button>

                                <span class="text-danger">{{ $errors->first('event_category_ticket_prices_ids') }}</span>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Ticket</th>
                                                <th>Early Bird</th>
                                                <th>Late & On-Site</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($category->tickets as $ticket)
                                            @php
                                            $earlyBirdPrice = $ticket->prices->firstWhere('event_category_ticket_name', 'Early Bird');
                                            $lateOnsitePrice = $ticket->prices->firstWhere('event_category_ticket_name', 'Late & On-site');
                                            $selectedPriceId = old('event_category_ticket_prices_ids.' . $category->id, $eventRegistrationHold->event_category_ticket_prices_ids[$category->id] ?? null);

                                            // Set your desired timezone
                                            $timezone = 'Asia/Kathmandu';
                                            $currentDate = \Carbon\Carbon::now($timezone);

                                            // Convert offer_price_end_date to the correct timezone
                                            $earlyBirdEndDate = $earlyBirdPrice ? \Carbon\Carbon::parse($earlyBirdPrice->offer_price_end_date)->setTimezone($timezone) : null;
                                            $lateOnsiteEndDate = $lateOnsitePrice ? \Carbon\Carbon::parse($lateOnsitePrice->offer_price_end_date)->setTimezone($timezone) : null;

                                            // Determine if Early Bird and Late On-Site prices should be enabled or disabled
                                            $isEarlyBirdDisabled = $earlyBirdEndDate ? $currentDate->greaterThanOrEqualTo($earlyBirdEndDate) : true;
                                            $isLateOnsiteDisabled = $lateOnsiteEndDate ? $currentDate->lessThan($lateOnsiteEndDate) : true;

                                            // Debugging: Output the values
                                            echo "<!-- Ticket Title: {$ticket->title} -->";
                                            echo "<!-- Early Bird Price: " . ($earlyBirdPrice ? $earlyBirdPrice->price : 'Not Found') . " -->";
                                            echo "<!-- Late On-site Price: " . ($lateOnsitePrice ? $lateOnsitePrice->price : 'Not Found') . " -->";
                                            echo "<!-- Selected Price ID: " . $selectedPriceId . " -->";
                                            echo "<!-- Early Bird Disabled: " . ($isEarlyBirdDisabled ? 'true' : 'false') . " -->";
                                            echo "<!-- Late On-site Disabled: " . ($isLateOnsiteDisabled ? 'true' : 'false') . " -->";
                                            @endphp
                                            <tr>
                                                <td width="50%">
                                                    {{ $ticket->title }}
                                                </td>
                                                <td>
                                                    @if ($earlyBirdPrice)
                                                    <div class="form-group col-lg-12" style="{{ $isEarlyBirdDisabled ? 'color: lightgray;' : '' }}">
                                                        <input class="form-check-input" type="radio" name="event_category_ticket_prices_ids[{{ $category->id }}]" id="ticket_{{ $ticket->id }}_early_bird" value="{{ $earlyBirdPrice->id }}" {{ $selectedPriceId == $earlyBirdPrice->id ? 'checked' : '' }} {{ $isEarlyBirdDisabled ? 'disabled' : '' }}>
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
                                                    <div class="form-group col-lg-12" style="{{ $isLateOnsiteDisabled ? 'color: lightgray;' : '' }}">
                                                        <input class="form-check-input" type="radio" name="event_category_ticket_prices_ids[{{ $category->id }}]" id="ticket_{{ $ticket->id }}_late_onsite" value="{{ $lateOnsitePrice->id }}" {{ $selectedPriceId == $lateOnsitePrice->id ? 'checked' : '' }} {{ $isLateOnsiteDisabled ? 'disabled' : '' }}>
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
                        @if(request()->query('hold_id'))
                        <input type="hidden" name="hold_id" value="{{ request()->query('hold_id') }}">
                        @endif
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
    function deselectRadioButton(categoryId) {
        const radioButtons = document.querySelectorAll(`input[name="event_category_ticket_prices_ids[${categoryId}]"]`);
        radioButtons.forEach(button => button.checked = false);
    }

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
        $('#search-nmc').on('click', function() {
            let query = $('#nmc_registration_number').val();
            if (query.length > 0) {
                $('#full_name, #address, #degree, #gender').addClass('loading');
                $.ajax({
                    url: "{{ route('search.nmc') }}",
                    type: "GET",
                    data: {
                        query: query
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#full_name, #address, #degree, #gender').removeClass('loading');

                        if (response.length > 0) {
                            let item = response[0];
                            $('#full_name').val(item.full_name);
                            $('#address').val(item.address);
                            $('#degree').val(item.degree);
                            $('#gender').val(item.gender);
                        } else {
                            $('#full_name, #address, #degree, #gender').val('');
                        }
                    },
                    error: function() {
                        $('#full_name, #address, #degree, #gender').removeClass('loading');
                        console.log('Error retrieving results.');
                    }
                });
            } else {
                $('#full_name, #address, #degree, #gender').val('').removeClass('loading');
            }
        });
    });
</script>
@endpush
