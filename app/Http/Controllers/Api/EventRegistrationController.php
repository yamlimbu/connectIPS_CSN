<?php

namespace Modules\Event\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Event\Services\EventRegistrationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\Event\Models\EventRegistrationHold;
use Modules\Event\Models\EventRegistration;
use Illuminate\Support\Facades\Log;
use Modules\Event\Models\EventRegistrationPaymentOrder;
use Modules\Event\Models\Event;
use GuzzleHttp\Client;

class EventRegistrationController extends Controller
{
    protected $eventRegistrationService;

    public function __construct(EventRegistrationService $eventRegistrationService)
    {
        $this->eventRegistrationService = $eventRegistrationService;

    }

    public function store(Request $request)
    {


        DB::beginTransaction();

        try {
            $event_id = $request->input('event_id');

            // Validate request data
            $validatedData = $request->validate([
                'nmc_registration_number' => 'nullable|string|max:255',
                'event_id' => 'required|exists:events,id',
                'event_category_ticket_prices_ids' => 'required|array|min:1',
                'event_category_ticket_prices_ids.*' => 'exists:event_category_tickets,id',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'email_address' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('event_registrations')->where(function ($query) use ($event_id) {
                        return $query->where('event_id', $event_id);
                    }),
                ],
                'phone_number' => 'nullable|string|max:255',
                'payment_method' => 'nullable|string|max:50',
                'payment_receipt' => 'nullable|string|max:255',
                'payment_status' => 'nullable|string|max:50',
                'transaction_id' => 'nullable|string|max:255',
                'total_amount' => 'required|numeric|min:0',
            ]);

            // Call service to register and process payment
            $data = $this->eventRegistrationService->registerAndProcessPayment($validatedData);

            // Check the returned data for status
            if ($data['status'] === 'success') {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => $data['message'],
                    'data' => $data,
                ], 201);
            } else {
                DB::commit();
                return response()->json([
                    'status' => 'error',
                    'message' => $data['message'],
                    'data' => $data['data'],
                ], 422);
            }

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the registration. Please retry payment',
                'error_code' => 'REGISTRATION_CREATION_ERROR'
            ], 422);
        }
    }


    public function retryPayment(Request $request)
    {


        $validatedData = $request->validate([
            'hold_id' => 'required|integer|exists:event_registration_holds,id'
        ]);
        $id = $request->hold_id;
        // Retrieve the EventRegistrationHold record
        $hold = EventRegistrationHold::where('id', $request->hold_id)
        ->where('status', '!=', 'paid')
        ->first();

        if (!$hold) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hold not found or already paid.'
            ], 422);
        }


        try {
            $paymentToken = decrypt($hold->payment_token);  // Decrypt the payment token
        } catch (\Exception $e) {
            Log::error('Failed to decrypt payment token', ['error' => $e->getMessage(), 'hold_id' => $id]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process payment token.'
            ], 422);
        }

        ///****$paymentSuccess = $this->paymentGateway->retry($paymentToken);  // Attempt to retry payment
        $paymentSuccess = true;
        //$paymentSuccess = false;

        $maxRetries = 3;  // Maximum retry attempts
        $retryAttempts = $hold->retry_attempts;

        Log::info('Payment retry attempt', ['hold_id' => $id, 'retry_attempts' => $retryAttempts]);

        if ($paymentSuccess) {
            $data = $this->eventRegistrationService->moveDataToEventRegistration($hold);  // Move data on successful payment

            // Optionally, you might want to reset retry attempts
            $hold->update([
                'status' => 'paid',
                'retry_attempts' => 0,
            ]);

            return [
                'status' => 'success',
                'message' => $data['message'],
                'registration' => $data['registration'],
                'payment_order' => $data['payment_order'],
                'qr_code' =>  $data['qr_code'],
            ];
        } else {
            // Check if retry attempts have exceeded the maximum limit
            if ($retryAttempts >= $maxRetries) {
                Log::warning('Payment failed after maximum retry attempts', ['hold_id' => $id]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment failed after multiple attempts. Please contact support'
                ], 422);
            }

            // Update the hold record to indicate that the retry failed
            $hold->update([
                'status' => 'retry_failed',
                'retry_attempts' => $retryAttempts + 1,
            ]);

            // Build the retry and support links

            $contactSupportUrl = 'contact-us.html';

            // Return JSON response with retry and support links
            return response()->json([
                'status' => 'error',
                'message' => sprintf(
                    'Payment failed again. Please retry payment or <a href="%s">contact support</a>.',

                    $contactSupportUrl
                ),

                'contact_support_url' => $contactSupportUrl,
                'retry_attempts' => $retryAttempts + 1
            ], 422);
        }
    }

    public function paymentSuccess()
    {
        // Handle successful payment
        echo 'Payemnt Successful';die;
    }

    public function paymentFail()
    {
        // Handle failed payment
        echo 'Payemnt Fail';die;
    }


}
