@extends('layouts.app')
@section('page-content')
    <section class="book_section layout_padding">
        <div class="container">
            <div class="row">
                <div class="col">
                    <form method="POST" action="{{ config('app.connect_ips_baseurl') }}">
                    <h3 style="text-align: center;"><span>{{$data['event_name']}}</span></h3>
                        @csrf
                        <input type="hidden" name="event_id" value="1">
                        <h4 class="mb-4"><span>Your Details</span></h4>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>NMC Number</strong> </label>:-
                                {{ $data['nmc_registration_number'] ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>First Name</strong> </label>:-
                                {{ $data['first_name'] ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>Middle Name</strong> </label>:-
                                {{ $data['middle_name'] ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>Last Name</strong> </label>:-
                                {{ $data['last_name'] ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>Email Address</strong> </label>:-
                                {{ $data['email_address'] ?? 'N/A' }}
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>Phone Number</strong> </label>:-
                                {{ $data['phone_number'] ?? 'N/A' }}
                            </div>
                        </div>

                        <h4 class="mb-4"><span>Ticket you want to purchase</span></h4>

                        <div class="form-row mb-4">
                            @foreach ($paymentDetails as $paymentDetail)
                                <div class="col-md-12">
                                    <strong>{{ $paymentDetail['category_title'] }}</strong>
                                    <div class="table-responsive">
                                        <table class="table table-bordered mt-3">
                                            <thead>
                                                <tr>
                                                    <th>Ticket</th>
                                                    <th>Early Bird (Till 20th Oct 2024)</th>
                                                    <th>Late & On-Site</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td width="50%">{{ $paymentDetail['title'] }}</td>
                                                    <td>{{ $paymentDetail['event_category_ticket_name'] == 'Early Bird' ? $paymentDetail['price'] : '-' }}
                                                    </td>
                                                    <td>{{ $paymentDetail['event_category_ticket_name'] == 'Late & On-site' ? $paymentDetail['price'] : '-' }}
                                                    </td>

                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                            <table class="table table-bordered mt-3">

                                <tbody>
                                    <tr>
                                        <td width="50%"><strong>Total</strong></td>
                                        <td width="50%">{{ number_format($data['txnamt'] / 100, 2, '.', ',') }}</td>
                                    </tr>
                                </tbody>
                            </table>


                        </div>

                        <!-- <h4 class="mb-4"><span>Payment Method</span></h4>
                        <div class="form-row border">
                            @if (isset($data['payment_method']) && $data['payment_method'] == 'connectIPS')
                                <div class="col-md-3">
                                    <div class="form-check img-box payment_icon">
                                        <input class="form-check-input" type="radio" name="payment_method" id="connectips"
                                            value="connectIPS"
                                            {{ $data['payment_method'] == 'connectIPS' ? 'checked' : '' }}
                                            style="margin-top: 15px;">
                                        <label class="form-check-label" for="connectips">
                                            <img src="{{ asset('images/connectips.png') }}" alt="ConnectIPS"
                                                width="90">
                                        </label>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-check img-box pull-left payment_icon">
                                        <input class="form-check-input" type="radio" name="payment_method" id="fonepay"
                                            value="fonepay" {{ $data['payment_method'] == 'fonepay' ? 'checked' : '' }}
                                            style="margin-top: 15px;">
                                        <label class="form-check-label" for="fonepay">
                                            <img src="{{ asset('images/fonepay.png') }}" alt="Fonepay" width="90">
                                        </label>
                                    </div>
                                </div>
                            @endif

                        </div> -->






                        <input type="hidden" name="MERCHANTID" id="MERCHANTID" value="{{ config('app.merchantid') }}" />


                        <input type="hidden" name="APPID" id="APPID" value="{{ config('app.appid') }}" />


                        <input type="hidden" name="APPNAME" id="APPNAME" value="{{ config('app.appname') }}" />


                        <input type="hidden" name="TXNID" id="TXNID" value="{{ $data['txnid'] }}" />


                        <input type="hidden" name="TXNDATE" id="TXNDATE" value="{{ $data['currentDate'] }}" />


                        <input type="hidden" name="TXNCRNCY" id="TXNCRNCY" value="NPR" />


                        <input type="hidden" name="TXNAMT" id="TXNAMT" value="{{ $data['txnamt'] }}" />


                        <input type="hidden" name="REFERENCEID" id="REFERENCEID" value="REF-001" />


                        <input type="hidden" name="REMARKS" id="REMARKS" value="RMKS-001" />


                        <input type="hidden" name="PARTICULARS" id="PARTICULARS" value="PART-001" />


                        <input type="hidden" name="TOKEN" id="TOKEN" value="{{ $data['token'] }}" />


                        <div class="form-row mt-4">
                            <div class="col-md-12">
                                <button type="submit" name="registration_form" value="submitted"
                                    class="btn btn-primary float-right">Proceed to Payment</button>
                                <a href="{{ route('event.register', 1) }}" class="btn btn-danger float-right"
                                    style="margin: 15px 10px;">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
