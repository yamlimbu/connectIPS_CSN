@extends('layouts.app')
@section('page-content')
<section class="register-section py-4 fixed-vh">
    <div class="container">
        <form method="POST" action="{{ config('app.connect_ips_baseurl') }}">
            @csrf
            <div class="card">
                <div class="card-header fw-bold p-2 p-md-3">Your Details</div>
                <div class="card-body p-2 p-md-3">
                    <input type="hidden" name="event_id" value="1">
                    <div class="row mb-2 mb-md-4">
                        <div class="form-group col-md-4">
                            <label class="form-label" for="nmc_registration_number"><strong>NMC Number</strong> </label>:-
                            {{ $data['nmc_registration_number'] ?? 'N/A' }}
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="nmc_registration_number"><strong>First Name</strong> </label>:-
                            {{ $data['first_name'] ?? 'N/A' }}
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="nmc_registration_number"><strong>Middle Name</strong> </label>:-
                            {{ $data['middle_name'] ?? 'N/A' }}
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="nmc_registration_number"><strong>Last Name</strong> </label>:-
                            {{ $data['last_name'] ?? 'N/A' }}
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="nmc_registration_number"><strong>Email Address</strong> </label>:-
                            {{ $data['email_address'] ?? 'N/A' }}
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label" for="nmc_registration_number"><strong>Phone Number</strong> </label>:-
                            {{ $data['phone_number'] ?? 'N/A' }}
                        </div>
                    </div>

                    <div>
                        @foreach ($paymentDetails as $paymentDetail)

                        <div class="mb-3">
                            <div class="heading mb-2">{{ $paymentDetail['category_title'] }}</div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Ticket</th>
                                            <th>Early Bird (Till 20th Oct 2024)</th>
                                            <th>Late & On-Site</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $paymentDetail['title'] }}</td>
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

                        <!-- <table class="table table-bordered ">
                            <tbody>
                                <tr>
                                    <td width="50%"><strong>Total</strong></td>
                                    <td width="50%">{{ number_format($data['txnamt'] / 100, 2, '.', ',') }}</td>
                                </tr>
                            </tbody>
                        </table> -->
                        <div class="d-flex gap-2 mb-4">
                            <div><strong>Total Amount:</strong></div>
                            <div>{{ number_format($data['txnamt'] / 100, 2, '.', ',') }}</div>
                        </div>


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
                                        <label class="form-label" class="form-check-label" for="connectips">
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
                                        <label class="form-label" class="form-check-label" for="fonepay">
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



                </div>
                <div class="card-footer text-end">
                    <button type="submit" name="registration_form" value="submitted" class="btn btn-primary me-2">Proceed to Payment</button>
                    <a href="{{ route('event.register', 1) }}" class="btn btn-danger">Back</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection