<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    //public $qrCodeBase64;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($registration)
    {
        $this->registration = $registration;
        //$this->qrCodeBase64 = $qrCodeBase64;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.event_code')
                    ->subject('Your Event Registration QR Code')
                    ->with([
                        'registration' => $this->registration,
                        //'qrCodeBase64' => $this->qrCodeBase64,
                    ]);
    }
}
