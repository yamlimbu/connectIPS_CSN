@extends('layouts.app')
@section('page-content')
    <section class="register-section py-4">
        <div class="container">

            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <div class="bg-light mb-5">
                <form method="POST" action="{{ route('event_register') }}">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ Crypt::encrypt($event_id) }}">
                    <div class="card mb-4">
                        <div class="card-header fw-bold text-uppercase p-2 p-md-3">Register</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-lg-4 mb-3">
                                    <label class="form-label" for="nmc_registration_number">NMC Number<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">

                                        <input type="text" class="form-control " id="nmc_registration_number"
                                            name="nmc_registration_number" placeholder=""
                                            value="{{ old('nmc_registration_number', $eventRegistrationHold['nmc_registration_number'] ?? '') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="search-nmc">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('nmc_registration_number'))
                                        <small class="text-danger">{{ $errors->first('nmc_registration_number') }}</small>
                                    @endif
                                </div>
                                <div class="form-group col-lg-4 mb-3">
                                    <label class="form-label" for="full_name">Full Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                        placeholder=""
                                        value="{{ old('full_name', $eventRegistrationHold['full_name'] ?? '') }}">
                                    @if ($errors->has('full_name'))
                                        <small class="text-danger">{{ $errors->first('full_name') }}</small>
                                    @endif
                                </div>
                                <div class="form-group col-lg-4 mb-3">
                                    <label class="form-label" for="address">Address<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder=""
                                        value="{{ old('address', $eventRegistrationHold['address'] ?? '') }}">
                                    @if ($errors->has('address'))
                                        <small class="text-danger">{{ $errors->first('address') }}</small>
                                    @endif
                                </div>
                                <div class="form-group col-lg-4 mb-3 mb-md-0">
                                    <label class="form-label" for="degree">Degree<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="degree" name="degree" placeholder=""
                                        value="{{ old('degree', $eventRegistrationHold['degree'] ?? '') }}">
                                    @if ($errors->has('degree'))
                                        <small class="text-danger">{{ $errors->first('degree') }}</small>
                                    @endif
                                </div>
                                <input type="hidden" class="form-control" id="gender" name="gender" placeholder=""
                                    value="{{ old('gender', $eventRegistrationHold['gender'] ?? '') }}">

                                <!-- <div class="form-group col-lg-4 mb-3">
                                    <label class="form-label" for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="{{ old('first_name', session('data.first_name')) }}">
                                    @if ($errors->has('first_name'))
    <span class="text-danger">{{ $errors->first('first_name') }}</span>
    @endif
                                </div>
                                <div class="form-group col-lg-4 mb-3">
                                    <label class="form-label" for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="" value="{{ old('middle_name', session('data.middle_name')) }}">
                                    @if ($errors->has('middle_name'))
    <span class="text-danger">{{ $errors->first('middle_name') }}</span>
    @endif
                                </div>
                                <div class="form-group col-lg-4 mb-3">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" value="{{ old('last_name', session('data.last_name')) }}">
                                    @if ($errors->has('last_name'))
    <span class="text-danger">{{ $errors->first('last_name', session('data.last_name')) }}</span>
    @endif
                                </div> -->
                                <div class="form-group col-lg-4 mb-3 mb-md-0">
                                    <label class="form-label" for="email_address">Email Address<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="email_address" name="email_address"
                                        placeholder=""
                                        value="{{ old('email_address', $eventRegistrationHold['email_address'] ?? '') }}">
                                    @if ($errors->has('email_address'))
                                        <small class="text-danger">{{ $errors->first('email_address') }}</small>
                                    @endif
                                </div>
                                <div class="form-group col-lg-4 mb-3 mb-md-0">
                                    <label class="form-label" for="phone_number">Phone Number<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                                        placeholder=""
                                        value="{{ old('phone_number', $eventRegistrationHold['phone_number'] ?? '') }}">
                                    @if ($errors->has('phone_number'))
                                        <small class="text-danger">{{ $errors->first('phone_number') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        @foreach ($data->eventCategories as $category)
                            <div class="card mb-2 mb-md-4">
                                <div
                                    class="card-header fw-bold text-uppercase p-2 p-md-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div>{{ $category->title }}</div>
                                        <small
                                            class="text-danger text-capitalize fw-normal">{{ $errors->first('event_category_ticket_prices_ids') }}</small>
                                    </div>

                                    <button type="button" onclick="deselectRadioButton({{ $category->id }})"
                                        class="btn btn-danger btn-sm pre-congress" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="Reset"><i class="fa fa-refresh"
                                            aria-hidden="true"></i></button>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Ticket</th>
                                                    @if ($category->tickets->first())
                                                        @foreach ($category->tickets->first()->prices->unique('priceType.id') as $price)
                                                            <th>{{ $price->priceType->name ?? '-' }}</th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($category->tickets as $ticket)
                                                    <tr>
                                                        <td width="50%">{{ $ticket->title }}</td>

                                                        @foreach ($ticket->prices as $price)
                                                            @php
                                                                $priceTypeName = $price->priceType->name ?? 'N/A';
                                                                $isPriceActive = $price->priceType->is_active ?? false;
                                                                $isChecked =
                                                                    old(
                                                                        'event_category_ticket_prices_ids.' .
                                                                            $category->id,
                                                                        $eventRegistrationHold[
                                                                            'event_category_ticket_prices_ids'
                                                                        ][$category->id] ?? '',
                                                                    ) == $price->id;
                                                            @endphp
                                                            <td>
                                                                <div class="form-group col-lg-12"
                                                                    style="{{ !$isPriceActive ? 'color: lightgray;' : '' }}">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="event_category_ticket_prices_ids[{{ $category->id }}]"
                                                                        id="ticket_{{ $ticket->id }}_{{ strtolower(str_replace(' ', '_', $priceTypeName)) }}"
                                                                        value="{{ $price->id }}"
                                                                        {{ $isPriceActive ? ($isChecked ? 'checked' : '') : 'disabled' }}>
                                                                    {{ $price->price }}
                                                                </div>
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        {{-- <table class="table table-bordered d-none">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Ticket</th>
                                                    @foreach ($category->tickets->first()->prices->unique('eventCategoryTicketPriceType.name') as $price)
                                                        <th>{{ $price->eventCategoryTicketPriceType->name }}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($category->tickets as $ticket)
                                                    <tr>
                                                        <td width="50%">
                                                            {{ $ticket->title }}
                                                        </td>
                                                        @foreach ($ticket->prices as $price)
                                                            @php
                                                                $priceTypeName =
                                                                    $price->eventCategoryTicketPriceType->name;
                                                                $isPriceActive =
                                                                    $price->eventCategoryTicketPriceType->is_active;
                                                                $isChecked =
                                                                    old(
                                                                        'event_category_ticket_prices_ids.' .
                                                                            $category->id,
                                                                        $eventRegistrationHold[
                                                                            'event_category_ticket_prices_ids'
                                                                        ][$category->id] ?? '',
                                                                    ) == $price->id;

                                                            @endphp
                                                            <td>
                                                                <div class="form-group col-lg-12"
                                                                    style="{{ !$isPriceActive ? 'color: lightgray;' : '' }}">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="event_category_ticket_prices_ids[{{ $category->id }}]"
                                                                        id="ticket_{{ $ticket->id }}_{{ strtolower(str_replace(' ', '_', $priceTypeName)) }}"
                                                                        value="{{ $price->id }}"
                                                                        {{ $isPriceActive ? ($isChecked ? 'checked' : '') : 'disabled' }}>
                                                                    {{ $price->price }}
                                                                </div>
                                                            </td>
                                                        @endforeach

                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table> --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (request()->query('hold_id'))
                        <input type="hidden" name="hold_id" value="{{ request()->query('hold_id') }}">
                    @endif
                    <div class="card-footer text-end border-0">
                        <button type="submit" name="registration_form" value="submitted"
                            class="btn btn-primary float-right">Register Now</button>
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
