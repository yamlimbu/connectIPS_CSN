<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EventRegistration;

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
        return $this->markdown('emails.event_code')
                    ->with([
                        'registration' => $this->registration,
                        'qrToken' => $this->qrToken,
                    ]);
    }
}
