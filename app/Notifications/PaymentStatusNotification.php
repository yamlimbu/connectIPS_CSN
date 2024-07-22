<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $txnid;
    protected $status;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($txnid, $status, $message)
    {
        $this->txnid = $txnid;
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Payment Validation Status')
                    ->line('Transaction ID: ' . $this->txnid)
                    ->line('Status: ' . $this->status)
                    ->line('Message: ' . $this->message)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'txnid' => $this->txnid,
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
