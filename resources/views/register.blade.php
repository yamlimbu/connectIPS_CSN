@extends('layouts.app')
@section('page-content')
    <section class="book_section layout_padding">
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

            <div class="row">
                <div class="col">
                    <form method="POST" action="{{ route('event_register') }}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event_id }}">
                        <h4><span>Registration Form</span></h4>
                        <div class="form-row ">
                            <div class="form-group col-lg-4">
                                <label for="nmc_registration_number">NMC Number</label>
                                <input type="text" class="form-control" id="nmc_registration_number"
                                    name="nmc_registration_number" placeholder=""
                                    value="{{ old('nmc_registration_number') }}">
                                @if ($errors->has('nmc_registration_number'))
                                    <span class="text-danger">{{ $errors->first('nmc_registration_number') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder=""
                                    value="{{ old('first_name') }}">
                                @if ($errors->has('first_name'))
                                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name"
                                    placeholder="" value="{{ old('middle_name') }}">
                                @if ($errors->has('middle_name'))
                                    <span class="text-danger">{{ $errors->first('middle_name') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder=""
                                    value="{{ old('last_name') }}">
                                @if ($errors->has('last_name'))
                                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="email_address">Email Address</label>
                                <input type="text" class="form-control" id="email_address" name="email_address"
                                    placeholder="" value="{{ old('email_address') }}">
                                @if ($errors->has('email_address'))
                                    <span class="text-danger">{{ $errors->first('email_address') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="" value="{{ old('phone_number') }}">
                                @if ($errors->has('phone_number'))
                                    <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                @endif
                            </div>
                        </div>
                        <h4><span>Ticket Type</span></h4>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="event_category_ticket_ids[1_1]" id="ticket_4" value="4">
                                        <label class="form-check-label" for="ticket_4">
                                            <strong>Pre Congress Registration</strong>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label>Nursing Conference (24th Oct, 2024)</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[1_1]" id="ticket_4" value="4">
                                                <label class="form-check-label" for="ticket_4">
                                                    Early bird -
                                                    1500.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[1_1]" id="ticket_3" value="3">
                                                <label class="form-check-label" for="ticket_3">
                                                    Late &amp; On-site -
                                                    3000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Post Graduate Course (24th Oct, 2024)</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[1_3]" id="ticket_6" value="6">
                                                <label class="form-check-label" for="ticket_6">
                                                    Early Bird -
                                                    20000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[1_3]" id="ticket_5" value="5">
                                                <label class="form-check-label" for="ticket_5">
                                                    Late &amp; On-site -
                                                    10000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="event_category_ticket_ids[1_1]" id="ticket_4" value="4">
                                        <label class="form-check-label" for="ticket_4">
                                            <strong>Main Congress </strong>

                                        </label>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Life Member of CSN</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_2]" id="ticket_1" value="1">
                                                <label class="form-check-label" for="ticket_1">
                                                    Early bird -
                                                    3000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_2]" id="ticket_2" value="2">
                                                <label class="form-check-label" for="ticket_2">
                                                    Late &amp; On-site -
                                                    1500.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nepali Delegates</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_4]" id="ticket_8" value="8">
                                                <label class="form-check-label" for="ticket_8">
                                                    Early Bird -
                                                    20000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_4]" id="ticket_7" value="7">
                                                <label class="form-check-label" for="ticket_7">
                                                    Late &amp; On-site -
                                                    12000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nurses / Residents</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_5]" id="ticket_10" value="10">
                                                <label class="form-check-label" for="ticket_10">
                                                    Early Bird -
                                                    10000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_5]" id="ticket_9" value="9">
                                                <label class="form-check-label" for="ticket_9">
                                                    Late &amp; On-site -
                                                    5000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="event_category_ticket_ids[1_1]" id="ticket_4" value="4">
                                        <label class="form-check-label" for="ticket_4">
                                            <strong>Pre Congress Registration</strong>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label>Nursing Conference (24th Oct, 2024)</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[1_1]" id="ticket_4" value="4">
                                                <label class="form-check-label" for="ticket_4">
                                                    Early bird -
                                                    1500.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[1_1]" id="ticket_3" value="3">
                                                <label class="form-check-label" for="ticket_3">
                                                    Late &amp; On-site -
                                                    3000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Post Graduate Course (24th Oct, 2024)</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[1_3]" id="ticket_6" value="6">
                                                <label class="form-check-label" for="ticket_6">
                                                    Early Bird -
                                                    20000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[1_3]" id="ticket_5" value="5">
                                                <label class="form-check-label" for="ticket_5">
                                                    Late &amp; On-site -
                                                    10000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="event_category_ticket_ids[1_1]" id="ticket_4" value="4">
                                        <label class="form-check-label" for="ticket_4">
                                            <strong>Main Congress </strong>

                                        </label>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Life Member of CSN</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_2]" id="ticket_1" value="1">
                                                <label class="form-check-label" for="ticket_1">
                                                    Early bird -
                                                    3000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_2]" id="ticket_2" value="2">
                                                <label class="form-check-label" for="ticket_2">
                                                    Late &amp; On-site -
                                                    1500.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nepali Delegates</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_4]" id="ticket_8" value="8">
                                                <label class="form-check-label" for="ticket_8">
                                                    Early Bird -
                                                    20000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_4]" id="ticket_7" value="7">
                                                <label class="form-check-label" for="ticket_7">
                                                    Late &amp; On-site -
                                                    12000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nurses / Residents</label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_5]" id="ticket_10" value="10">
                                                <label class="form-check-label" for="ticket_10">
                                                    Early Bird -
                                                    10000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="event_category_ticket_ids[2_5]" id="ticket_9" value="9">
                                                <label class="form-check-label" for="ticket_9">
                                                    Late &amp; On-site -
                                                    5000.00
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}



                        <div class="form-row">

                            {{-- <div class="form-row">
                                @isset($data['eventCategories'])
                                    @foreach ($data['eventCategories'] as $category)
                                        @if (!empty($category['tickets']))
                                            @foreach ($category['tickets'] as $ticketIndex => $ticket)
                                                @if (!empty($ticket['tickets']))
                                                    <div class="form-group col-lg-12">
                                                        <label>{{ $category['title'] . '/' . $ticket['title'] }}</label>
                                                        <div class="row">
                                                            @php
                                                                // Sort tickets to have "Early bird" first
                                                                usort($ticket['tickets'], function ($a, $b) {
                                                                    return $a['event_category_ticket_name'] ===
                                                                        'Early bird'
                                                                        ? -1
                                                                        : 1;
                                                                });
                                                            @endphp
                                                            @foreach ($ticket['tickets'] as $subTicket)
                                                                <div class="col-md-4">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio"
                                                                                    name="event_category_ticket_ids[{{ $category['id'] }}_{{ $ticket['id'] }}]"
                                                                                    id="ticket_{{ $subTicket['id'] }}"
                                                                                    value="{{ $subTicket['id'] }}">
                                                                                <label class="form-check-label"
                                                                                    for="ticket_{{ $subTicket['id'] }}">
                                                                                    <strong>{{ $subTicket['event_category_ticket_name'] }}</strong><br>
                                                                                    <span>{{ $subTicket['price'] }}</span>
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @if ($errors->has('event_category_ticket_ids'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('event_category_ticket_ids') }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endisset
                            </div> --}}


                            {{-- @isset($data['eventCategories'])
                                @foreach ($data['eventCategories'] as $ticketIndex => $category)
                                    @if (!empty($category['tickets']))
                                        @foreach ($category['tickets'] as $ticket)
                                            @if (!empty($ticket['tickets']))
                                                <div class="form-group col-lg-4">
                                                    <label
                                                        for="ticket_{{ $ticket['id'] }}">{{ $category['title'] . '/' . $ticket['title'] }}</label>
                                                    <select name="event_category_ticket_ids[{{ $ticketIndex }}]"
                                                        class="form-control wide" id="ticket_{{ $ticket['id'] }}">
                                                        @php
                                                            usort($ticket['tickets'], function ($a, $b) {
                                                                return $a['event_category_ticket_name'] === 'Early bird'
                                                                    ? -1
                                                                    : 1;
                                                            });
                                                        @endphp
                                                        @foreach ($ticket['tickets'] as $subTicket)
                                                            <option value="{{ $subTicket['id'] }}">
                                                                {{ $subTicket['event_category_ticket_name'] }} -
                                                                {{ $subTicket['price'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('event_category_ticket_ids'))
                                                        <span
                                                            class="text-danger">{{ $errors->first('event_category_ticket_ids') }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endisset --}}


                        </div>

                        <h4>
                            <span>Payment Method</span>
                        </h4>
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-check img-box ">
                                    {{-- ConnectIPS  --}}
                                    <input class="form-check-input" type="radio" name="payment_option" id="connectips"
                                        value="connectips">
                                    <label class="form-check-label" for="connectips">
                                        <img src="{{ asset('images/connectips.png') }}" alt="ConnectIPS" width="100">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check img-box pull-left">
                                    {{-- FonePay --}}
                                    <input class="form-check-input" type="radio" name="payment_option" id="fonepay"
                                        value="fonepay">
                                    <label class="form-check-label" for="fonepay">
                                        <img src="{{ asset('images/fonepay.png') }}" alt="Fonepay" width="100">
                                    </label>
                                </div>
                            </div>
                        </div>




                        <input type="hidden" name="payment_receipt" id="payment_receipt" value="434RR">
                        <input type="hidden" name="payment_status" id="payment_status" value="pending">

                        <input type="hidden" name="total_amount" id="total_amount" value="300">
                        <input type="hidden" name="transaction_id" id="transaction_id" value="123">


                        <div class="form-row">
                            <div class="btn-box">
                                <button type="submit pull-right" name="registration_form" value="submitted"
                                    class="btn">Submit Now</button>
                            </div>
                        </div>


                    </form>


                </div>
            </div>
        </div>
    </section>
@endsection
