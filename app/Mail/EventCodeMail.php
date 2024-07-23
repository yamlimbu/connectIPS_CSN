<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Storage;

class EventCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $qrToken;

    public function __construct(EventRegistration $registration, $qrToken)
    {
        $this->registration = $registration;
        $this->qrToken = $qrToken;
    }

    public function build()
    {
        // Define the path to the PNG QR code
        $pngPath = 'qrcodes/' . $this->qrToken;

        // Attach the PNG QR code to the email
        return $this->markdown('emails.event_code')
                    ->with([
                        'registration' => $this->registration,
                        'qrToken' => $this->qrToken,
                    ])
                    ->attach(Storage::path($pngPath), [
                        'as' => 'qr_code.png',
                        'mime' => 'image/png',
                    ]);
    }
}
