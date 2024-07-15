@extends('layouts.app')
@section('page-content')
    <section class="book_section layout_padding">
        <div class="container">


            <div class="row">
                <div class="col">
                    <form method="POST" action="{{ route('final_submit') }}">
                        @csrf
                        <input type="hidden" name="event_id" value="1">
                        <h4 class="mb-4"><span>Registration Form Preview</span></h4>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>NMC Number</strong> </label>:- 122222
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>First Name</strong> </label>:- Rajendra
                                Jha
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>Middle Name</strong> </label>:- Kumar
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>Last Name</strong> </label>:- Jha
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>Email Address</strong> </label>:-
                                1233@gmail.com
                            </div>
                        </div>


                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nmc_registration_number"><strong>Phone Number</strong> </label>:- +9779851148062
                            </div>
                        </div>

                        <h4 class="mb-4"><span>Ticket Type</span></h4>

                        <div class="form-row mb-4">
                            <div class="col-md-12">
                                <strong>Pre Congress Registration</strong>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>Ticket Name</th>
                                            <th>Early Bird</th>
                                            <th>Late & On-site</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="50%">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="event_category_ticket_ids[0]" id="ticket_1" value="1"
                                                        checked>
                                                    <label class="form-check-label" for="ticket_1">
                                                        Nursing Conference (24th Oct, 2024)
                                                    </label>
                                                </div>
                                            </td>
                                            <td>1500.00</td>
                                            <td>-</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <strong>Main Congress (25th &amp; 26th Oct, 2024)</strong>
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>Ticket Name</th>
                                            <th>Early Bird</th>
                                            <th>Late & On-site</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td width="50%">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="event_category_ticket_ids[1]" id="ticket_2" value="2"
                                                        checked>
                                                    <label class="form-check-label" for="ticket_2">
                                                        Life Member of CSN
                                                    </label>
                                                </div>
                                            </td>
                                            <td>3000.00</td>
                                            <td>-</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h4 class="mb-4"><span>Payment Method</span></h4>

                        <div class="form-row border">
                            <div class="col-md-3">
                                <div class="form-check img-box">
                                    <input class="form-check-input" type="radio" name="payment_method" id="connectips"
                                        value="connectips" checked>
                                    <label class="form-check-label" for="connectips">
                                        <img src="{{ asset('images/connectips.png') }}" alt="ConnectIPS" width="90">
                                    </label>
                                </div>
                            </div>

                        </div>

                        <input type="hidden" name="payment_receipt" id="payment_receipt" value="434RR">
                        <input type="hidden" name="payment_status" id="payment_status" value="pending">
                        <input type="hidden" name="total_amount" id="total_amount" value="300">
                        <input type="hidden" name="transaction_id" id="transaction_id" value="123">

                        <div class="form-row mt-4">
                            <div class="col-md-12">
                                <button type="submit" name="registration_form" value="submitted"
                                    class="btn btn-primary float-right">Final Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
