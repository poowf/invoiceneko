<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use PDF;

class InvoiceNotification extends Notification implements ShouldQueue
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
        $pdf = $invoice->generatePDFView();
        //Cast to string to ensure only a string is returned
        $token = (string) $invoice->generateShareToken();
        $url = route('invoice.token', ['token' => $token]);
        $invoice_slug = str_slug($invoice->nice_invoice_id) . '.pdf';

        return (new MailMessage)
                    ->subject("New Invoice #{$invoice->nice_invoice_id} from {$company->name}")
                    ->greeting("Hello {$client->companyname}!")
                    ->line('You have a new Invoice.')
                    ->action('View Invoice', $url)
                    ->line('Thank you for using our application!')
                    ->attachData($pdf->inline($invoice_slug), $invoice_slug);
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
