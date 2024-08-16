<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $qrToken;
    public $qrGatePass;

    public function __construct($registration)
    {
        $this->registration = $registration;
        $this->qrToken = $this->generateQrCode();
        $this->qrGatePass = $this->generateQrGatepass();
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
    $url = route('eventregistrations.gatepass.details', ['event_token' => $this->registration->event_token]);

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
        $filePathGatePass = 'gatepass/' . $this->registration->event_token . '.png';

        return $this->subject('Thank You for Your Registration for the ' . $this->registration->event->name)
        ->view('emails.event_code')
                        ->with([
                        'registration' => $this->registration,
                        'qrToken' => $this->qrToken,
                        'qrGatePass' => $this->qrGatePass,

                    ])->attach(storage_path('app/public/' . $filePathToken), [
                        'as' => 'qrcode.png',
                        'mime' => 'image/png',
                    ])->attach(storage_path('app/public/' . $filePathGatePass), [
                        'as' => 'gatepass.png',
                        'mime' => 'image/png',
                    ]);;
    }
}
