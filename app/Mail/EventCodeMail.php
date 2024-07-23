<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class EventCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $qrToken;

    public function __construct($registration, $qrToken)
    {
        $this->registration = $registration;
        $this->qrToken = $qrToken;
    }

    public function build()
    {
        // Define the file path for the PNG QR code
        $pngPath = $this->qrToken;

        // Get the absolute path to the QR code file
        $absolutePath = Storage::disk('public')->path($pngPath);

        // Attach the PNG QR code to the email
        return $this->markdown('emails.event_code')
                    ->with([
                        'registration' => $this->registration,
                    ])
                    ->attach($absolutePath, [
                        'as' => 'qr_code.png',
                        'mime' => 'image/png',
                    ]);
    }
}

