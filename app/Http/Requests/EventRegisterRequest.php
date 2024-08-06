<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AtLeastOneTicket;
use Illuminate\Validation\Rule;
use App\Models\EventCategory;
class EventRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $event_id = $this->input('event_id');
        $categories = EventCategory::all();
        return [
            'nmc_registration_number' => 'required|string',
            // 'first_name' => 'required|string',
            // 'middle_name' => 'nullable|string',
            // 'last_name' => 'required|string',
            'full_name' => 'required|string',
            'address' => 'required|string',
            'degree' => 'required|string',
            'gender' => 'nullable|string',
            'email_address' => [
                'required',
                'email',
                'max:255',
                Rule::unique('event_registrations')->where(function ($query) use ($event_id) {
                    return $query->where('event_id', $event_id);
                }),
            ],
            'phone_number' => 'required|string',
            'event_category_ticket_prices_ids' => [
                'required',
                'array',
                new AtLeastOneTicket($categories),
            ],
            'event_category_ticket_prices_ids.*' => 'exists:event_category_ticket_prices,id',
            // 'payment_method' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'event_category_ticket_prices_ids.required' => 'Please select a ticket type',
            'nmc_registration_number.required' => 'Please enter your NMC Registration Number.',
            'first_name.required' => 'Please enter your First Name.',
            'last_name.required' => 'Please enter your Last Name.',
            'email_address.required' => 'Please enter your Email Address.',
            'email_address.email' => 'Please enter a valid Email Address.',
            'email_address.unique' => 'This email address has already been registered for the selected event. Please enter a different email',
            'phone_number.required' => 'Please enter your Phone Number.',
            'full_name.required' => 'Please enter your Full Name.',
            'address.required' => 'Please enter your Address.',
            'degree.required' => 'Please enter your Degree.',

            // 'payment_method.required' => 'Please select a Payment Method.',
        ];
    }
}
