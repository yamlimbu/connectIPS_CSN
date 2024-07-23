<?php

namespace App\Helpers;

use App\Models\EventRegistrationHold;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\EventCodeMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RecordHelper
{
    /**
     * Map fields from EventRegistrationHold to EventRegistration.
     *
     * @param EventRegistrationHold $hold
     * @return array
     */
    public static function mapFields($hold)
    {
        return [
            'hold_id' => $hold->id,
            'event_id' => $hold->event_id,
            'nmc_registration_number' => $hold->nmc_registration_number,
            'first_name' => $hold->first_name,
            'middle_name' => $hold->middle_name,
            'last_name' => $hold->last_name,
            'email_address' => $hold->email_address,
            'phone_number' => $hold->phone_number,
            'payment_details' => $hold->payment_details,
            'payment_method' => $hold->payment_method,
            'total_amount' => $hold->total_amount,
            'status' => 'success',
            'ip_address' => $hold->ip_address,
            'device' => $hold->device,
            'platform' => $hold->platform,
            'browser' => $hold->browser,
            'txnid' => $hold->txnid,
            'txndate' => $hold->txndate,
            'txncrncy' => $hold->txncrncy,
            'txnamt' => $hold->txnamt,
            'referenceid' => $hold->referenceid,
            'remarks' => $hold->remarks,
            'particulars' => $hold->particulars,
            'token' => $hold->token,
            'event_category_id' => $hold->event_category_id,
            'event_category_ticket_id' => $hold->event_category_ticket_id,
            'event_category_ticket_price_id' => $hold->event_category_ticket_price_id,
            'event_category_id_two' => $hold->event_category_id_two,
            'event_category_ticket_id_two' => $hold->event_category_ticket_id_two,
            'event_category_ticket_price_id_two' => $hold->event_category_ticket_price_id_two,
            'browser_version' => $hold->browser_version,
            'is_mobile' => $hold->is_mobile,
            'is_tablet' => $hold->is_tablet,
            'is_desktop' => $hold->is_desktop,
            'is_bot' => $hold->is_bot,
            'is_iphone' => $hold->is_iphone,
            'is_android' => $hold->is_android,
        ];
    }

    /**
     * Copy a record from EventRegistrationHold to EventRegistration.
     *
     * @param int $holdId
     * @return bool
     */
    public static function copyRecord($holdId)
    {
        DB::beginTransaction();

        try {

            $hold = EventRegistrationHold::where('id', $holdId)->first();

            if (!$hold) {
                DB::rollBack();
                return ['success' => false, 'message' => 'Record not found'];
            }
            // Check if a record already exists with the same hold_id, event_id, and txnid
            // $existingRecord = EventRegistration::where('hold_id', $hold->id)
            //                                     ->where('event_id', $hold->event_id)
            //                                     ->where('txnid', $hold->txnid)
            //                                     ->first();

            // if ($existingRecord) {
            //     DB::rollBack();
            //     return ['success' => false, 'message' => 'Record already exists with the same hold_id, event_id, and txnid'];
            // }
            $data = self::mapFields($hold);
            $registration = EventRegistration::create($data);

             // Generate QR code as base64
             $qrToken = self::generateQrCode($registration->event_token);

            // Send email with QR code
            try {
                // Send email with QR code
                Mail::to($registration->email_address)->send(new EventCodeMail($registration, $qrToken));

                // Log the successful email sending event
                Log::channel('transaction')->info('Email sent to ' . $registration->email_address . ' with event token ' . $registration->event_token);
            } catch (\Exception $e) {
                // Log the error if email sending fails
                Log::channel('transaction')->error('Failed to send email to ' . $registration->email_address . ': ' . $e->getMessage());
            }
            DB::commit();
            return ['success' => true, 'message' => 'Record copied successfully'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Failed to copy record: ' . $e->getMessage()];
        }
    }
        /**
     * Generate a QR code and convert to base64.
     *
     * @param string $eventToken
     * @return string
     */

    public static function generateQrCode($eventToken)
    {
        try {
            // Generate QR code in PNG format
            $qrCode = QrCode::format('png')->size(200)->generate($eventToken);

            // Define the file path for the PNG QR code
            $filePath = 'qrcodes/' . $eventToken . '.png';

            // Store the QR code image in the public disk
            Storage::disk('public')->put($filePath, $qrCode);

            Log::info('QR code generated and stored at: ' . $filePath);

            // Return the path to the PNG file
            return $filePath;
        } catch (\Exception $e) {
            Log::error('Failed to generate QR code: ' . $e->getMessage());
            throw $e;
        }
    }


}
