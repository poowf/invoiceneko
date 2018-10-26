<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use PDF;

class NewInvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $invoice;

    /**
     * Create a new notification instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $invoice = $this->invoice;
        $company = $invoice->company;
        $client = $invoice->client;
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        $token = $invoice->generateShareToken();
        $url = route('invoice.token', ['token' => $token]);

        return (new MailMessage)
                    ->subject("New Invoice #{$invoice->nice_invoiceid} from {$company->name}")
                    ->greeting('Hello!')
                    ->line('You have a new Invoice.')
                    ->action('View Invoice', $url)
                    ->line('Thank you for using our application!')
                    ->attachData($pdf->inline(str_slug($invoice->nice_invoice_id) . '.pdf'));
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
            //
        ];
    }
}
