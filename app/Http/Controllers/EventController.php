<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\EventRegisterRequest;
use App\Services\ApiService;

class EventController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }


    public function index()
    {
        // Fetch data from the API
        $response = $this->apiService->get('/events');


        // Decode the JSON response
        $events = $response->json('data');

        // Pass data to the view
        return view('welcome', compact('events'));
    }


    public function register($event_id)
    {
        // Fetch data from the API
        $response = $this->apiService->get('/event/1/tickets');


        // Decode the JSON response
        $data = $response->json('data');

        // Pass data to the view
        return view('register', compact('data', 'event_id'));
    }

    public function details($event_id)
    {
        // Fetch data from the API
        $response = $this->apiService->get('/events');


        // Decode the JSON response
        $data = $response->json('data');
        // Pass data to the view
        return view('event_details', compact('data', 'event_id'));
    }





    function event_register(EventRegisterRequest $request)
    {
        if ($request->registration_form == 'submitted') {
            return redirect(route('preview'));
        }
    }

    function final_submit(Request $request)
    {
        if ($request->registration_form == 'submitted') {
            // dd($request->all());
            $postdata = [
                'event_id' => $request->input('event_id'),
                'nmc_registration_number' => $request->input('nmc_registration_number'),
                'first_name' => $request->input('first_name'),
                'middle_name' => $request->input('middle_name'),
                'last_name' => $request->input('last_name'),
                'email_address' => $request->input('email_address'),
                'phone_number' => $request->input('phone_number'),
                'event_category_ticket_ids[0]' => 1,
                'event_category_ticket_ids[1]' => 3,
                'payment_method' => $request->input('payment_method'),
                'payment_receipt' => $request->input('payment_receipt'),
                'payment_status' => $request->input('payment_status'),

                'total_amount' => $request->input('total_amount'),
                'transaction_id' => $request->input('transaction_id'),
            ];

            // dd($postdata);
            // $response = $this->apiService->post('/event/register', $postdata);

            return redirect(route('success'));


            // dd($response->json());

            // if ($response->successful()) {
            //     return redirect()->back()->with('success', 'Registration successful!');
            // } else {
            //     return redirect()->back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
            // }
        }
    }



    public function preview()
    {
        // Return the success view
        return view('preview');
    }

    public function success()
    {
        // Set a success message in the session
        session()->flash('success', "Transaction has been successfully completed.");

        // Return the success view
        return view('success');
    }


    public function fail()
    {
        // Set a success message in the session
        session()->flash('error', "Transaction has been terminated.");

        // Return the success view
        return view('fail');
    }
}
