<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class InvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invoice;

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
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $invoice = $this->invoice;
        $company = $invoice->company;
        $client = $invoice->getClient();
        $pdf = $invoice->generatePDFView();
        //Cast to string to ensure only a string is returned
        $token = (string) $invoice->generateShareToken();
        $url = route('invoice.token', ['token' => $token]);
        $pixelRoute = route('notification.pixel', ['notification_id' => $this->id]);
        $invoice_slug = Str::slug($invoice->nice_invoice_id).'.pdf';

        return (new NekoMailMessage())
                    ->subject("New Invoice #{$invoice->nice_invoice_id} from {$company->name}")
                    ->greeting("Hello {$client->companyname}!")
                    ->line("You have a new Invoice from {$company->name}")
                    ->action('View Invoice', $url)
                    ->line('Thank you for using our application!')
                    ->content('<img src="'.$pixelRoute.'">')
                    ->attachData($pdf->inline($invoice_slug), $invoice_slug);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'invoice_id'      => $this->invoice->id,
            'nice_invoice_id' => $this->invoice->nice_invoice_id,
            'company_id'      => $this->invoice->company_id,
            'clientname'      => $this->invoice->client->companyname,
            'email'           => $this->invoice->client->contactemail,
        ];
    }
}
