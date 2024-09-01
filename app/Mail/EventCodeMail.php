<?php

namespace App\Mail;

use App\Models\EventCategoryTicket;
use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Dompdf\Dompdf;
use Dompdf\Options;


class EventCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $qrToken;
    public $pdfPath;

    public function __construct($registration)
    {
        $this->registration = $registration;
        $this->qrToken = $this->generateQrCode();
        $this->pdfPath = $this->generatePdfWithQrCode();
    }

    private function generateQrCode()
    {
        $qrCodeData = QrCode::format('png')->size(300)->generate($this->registration->event_token);
        $filePath = 'qrcodes/' . $this->registration->event_token . '.png';
        Storage::disk('public')->put($filePath, $qrCodeData);
        return $filePath;
    }

    private function generatePdfWithQrCode()
    {
        // Get the QR code image data
        $qrCodePath = storage_path('app/public/' . $this->qrToken);
        $qrCodeImage = file_get_contents($qrCodePath);
        $qrCodeBase64 = base64_encode($qrCodeImage);

        // Full name in uppercase (as seen in the PDF)
        $fullName = strtoupper($this->registration->full_name);

        // Define the HTML content for the PDF with the Base64 image and styled name
        $html = '
            <html>
                <body>
                    <div style="text-align: center;">
                        <img src="data:image/png;base64,' . $qrCodeBase64 . '" alt="QR Code">
                       <p style="font-family: Helvetica, Arial, sans-serif; font-size: 23px; text-align: center; text-transform: uppercase; line-height: 1.5; margin: 10px 0;">
    ' . htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') . '
</p>


                    </div>
                </body>
            </html>';

        // Initialize Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save the PDF to storage
        $filePath = 'pdfs/' . $this->registration->event_token . '.pdf';
        Storage::disk('public')->put($filePath, $dompdf->output());

        return $filePath;
    }








    public function build()
    {
        $eventRegistration = EventRegistration::findOrFail($this->registration->id);
        $ticketPriceIds = array_values($eventRegistration->event_category_ticket_prices_ids);

        $eventCategoryTickets = EventCategoryTicket::select('event_category_tickets.*')
            ->join('event_category_ticket_prices', 'event_category_ticket_prices.event_category_ticket_id', '=', 'event_category_tickets.id')
            ->whereIn('event_category_ticket_prices.id', $ticketPriceIds)
            ->distinct()
            ->get();

        $subject = 'Thank You for Your Registration for the ' . $eventCategoryTickets[0]->title;
        if (count($eventCategoryTickets) > 1) {
            $subject .= ' and ' . $this->registration->event->name;
        }

        return $this->subject($subject)
            ->view('emails.event_code')
            ->with([
                'registration' => $this->registration,
                'qrToken' => $this->qrToken,
                'eventCategoryTickets' => $eventCategoryTickets,
            ])
            ->attach(storage_path('app/public/' . $this->pdfPath), [
                'as' => 'event_qr_code.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
