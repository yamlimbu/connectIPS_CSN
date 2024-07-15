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
                            @isset($data['eventCategories'])
                                @foreach ($data['eventCategories'] as $category)
                                    @if (!empty($category['tickets']))
                                        <div class="col-md-12">
                                            <strong>{{ $category['title'] }}</strong>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Ticket Name</th>
                                                        <th>Early Bird</th>
                                                        <th>Late & On-site</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($category['tickets'] as $ticket)
                                                        @if (!empty($ticket['tickets']))
                                                            <tr>
                                                                <td width="50%">
                                                                    <div class="form-group col-lg-12">

                                                                        @if ($category['id'] == 1)
                                                                            <input class="form-check-input" type="radio"
                                                                                name="event_category_ticket_ids[0]"
                                                                                id="ticket_{{ $ticket['id'] }}"
                                                                                value="{{ $ticket['id'] }}">
                                                                        @endif

                                                                        @if ($category['id'] == 2)
                                                                            <input class="form-check-input" type="radio"
                                                                                name="event_category_ticket_ids[1]"
                                                                                id="ticket_{{ $ticket['id'] }}"
                                                                                value="{{ $ticket['id'] }}">
                                                                        @endif



                                                                        {{ $ticket['title'] }}
                                                                    </div>

                                                                </td>
                                                                @php
                                                                    $early_bird =
                                                                        $ticket['tickets'][0]['price'] ?? 'N/A';
                                                                    $late_on_site =
                                                                        $ticket['tickets'][1]['price'] ?? 'N/A';
                                                                @endphp
                                                                <td>{{ $early_bird }}</td>
                                                                <td>{{ $late_on_site }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @endforeach
                            @endisset
                        </div>


                        <h4>
                            <span>Payment Method</span>
                        </h4>
                        <div class="form-row border">
                            <div class="col-md-3">
                                <div class="form-check img-box">
                                    <input class="form-check-input" type="radio" name="payment_method" id="connectips"
                                        value="connectips" {{ old('payment_method') == 'connectips' ? 'checked' : '' }}
                                        style="margin-top: 30px;">
                                    <label class="form-check-label" for="connectips">
                                        <img src="{{ asset('images/connectips.png') }}" alt="ConnectIPS" width="90">
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check img-box pull-left">
                                    <input class="form-check-input" type="radio" name="payment_method" id="fonepay"
                                        value="fonepay" {{ old('payment_method') == 'fonepay' ? 'checked' : '' }}
                                        style="margin-top: 30px;">
                                    <label class="form-check-label" for="fonepay">
                                        <img src="{{ asset('images/fonepay.png') }}" alt="Fonepay" width="90">
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('payment_method'))
                                <span class="text-danger">{{ $errors->first('payment_method') }}</span>
                            @endif
                        </div>



                        <input type="hidden" name="payment_receipt" id="payment_receipt" value="434RR">
                        <input type="hidden" name="payment_status" id="payment_status" value="pending">
                        <input type="hidden" name="total_amount" id="total_amount" value="300">
                        <input type="hidden" name="transaction_id" id="transaction_id" value="123">



                        <div class="form-row mt-4">
                            <div class="col-md-12">
                                <button type="submit" name="registration_form" value="submitted"
                                    class="btn btn-primary float-right">Register Now</button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.ticket-options-select').on('change', function() {
                var selectedId = $(this).attr('id');
                var value = $(this).val();
                console.log(selectedId + ' option selected:', value);
                $('[id^="' + selectedId + '_container"]').addClass('d-none');
                if (value) {
                    $('#' + selectedId + '_container').removeClass('d-none');
                }
            });

            // Initial trigger to set the state based on current selections
            $('.ticket-options-select').trigger('change');
        });
    </script>





    <script>
        $(document).ready(function() {
            $('#nursing_conference_post_graduate_course').on('change', function() {
                var value = $(this).val();
                console.log('Nursing/PG course selected:', value);
                $('#nursing_conference_options').addClass('d-none');
                $('#post_graduate_course_options').addClass('d-none');
                if (value === 'nursing_conference') {
                    $('#nursing_conference_options').removeClass('d-none');
                } else if (value === 'post_graduate_course') {
                    $('#post_graduate_course_options').removeClass('d-none');
                }
            });

            $('#main_congress_options').on('change', function() {
                var value = $(this).val();
                console.log('Main congress option selected:', value);
                $('#life_member_options').addClass('d-none');
                $('#nepali_delegates_options').addClass('d-none');
                $('#nurses_residents_options').addClass('d-none');
                if (value === 'life_member') {
                    $('#life_member_options').removeClass('d-none');
                } else if (value === 'nepali_delegates') {
                    $('#nepali_delegates_options').removeClass('d-none');
                } else if (value === 'nurses_residents') {
                    $('#nurses_residents_options').removeClass('d-none');
                }
            });
        });
    </script>
@endpush
