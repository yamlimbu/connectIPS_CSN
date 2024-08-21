<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Helpers\EventHelper;
use App\Models\EventCategoryTicket;
use App\Models\EventRegistration;

class EventCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $qrToken;

    public function __construct($registration)
    {
        $this->registration = $registration;
        $this->qrToken = $this->generateQrCode();
    }

    public function generateQrCode()
    {
        // Create the QR code
        $qrCodeData = QrCode::format('png')->size(300)->generate($this->registration->event_token);

        // Define the file path
        $filePath = 'qrcodes/' . $this->registration->event_token . '.png';

        // Save the QR code to storage
        Storage::disk('public')->put($filePath, $qrCodeData);

        return $filePath;
    }
    public function generateQrGatepass()
    {
        // Create the QR code
        $url = route('api/v1/eventregistrations.gatepass.details', ['event_token' => $this->registration->event_token]);

        // Create the QR code for the details page
        $qrCodeData = QrCode::format('png')->size(300)->generate($url);

        // Define the file path
        $filePath = 'gatepass/' . $this->registration->event_token . '.png';

        // Save the QR code to storage
        Storage::disk('public')->put($filePath, $qrCodeData);

        return $filePath;
    }

    public function build()
    {
        $filePathToken = 'qrcodes/' . $this->registration->event_token . '.png';

        $eventRegistration = EventRegistration::findOrFail($this->registration->id);

        // Decode the JSON 'event_category_ticket_prices_ids' column into an array
        $ticketPriceIds = array_values($eventRegistration->event_category_ticket_prices_ids);

        // Fetch event category tickets using the related `event_category_ticket_price` records
        $eventCategoryTickets = EventCategoryTicket::select('event_category_tickets.*')
            ->join('event_category_ticket_prices', 'event_category_ticket_prices.event_category_ticket_id', '=', 'event_category_tickets.id')
            ->whereIn('event_category_ticket_prices.id', $ticketPriceIds)
            ->distinct()  // Ensure distinct results in case of multiple matches
            ->get();
        if (count($eventCategoryTickets) > 1) {
            $subject = 'Thank You for Your Registration for the ' . $eventCategoryTickets[0]->title . ' and ' . $this->registration->event->name;
        } else {
            $subject = 'Thank You for Your Registration for the ' . $eventCategoryTickets[0]->title;
        }

        return $this->subject($subject)
            ->view('emails.event_code')
            ->with([
                'registration' => $this->registration,
                'qrToken' => $this->qrToken,
                'eventCategoryTickets' => $eventCategoryTickets

            ])->attach(storage_path('app/public/' . $filePathToken), [
                'as' => 'qrcode.png',
                'mime' => 'image/png',
            ]);
    }
}
