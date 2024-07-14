<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nmc_registration_number' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email_address' => 'required|email',
            'phone_number' => 'required|string',
            'event_category_ticket_ids' => 'required|array',
            'event_category_ticket_ids.*' => 'required|string',
            'total_amount' => 'required|numeric',
            'transaction_id' => 'required|string',
        ];
    }
}
