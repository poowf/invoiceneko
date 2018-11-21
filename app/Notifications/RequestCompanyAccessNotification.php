<?php

namespace App\Notifications;

use App\Models\CompanyUserRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RequestCompanyAccessNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $companyUserRequest;

    /**
     * Create a new notification instance.
     *
     * @param CompanyUserRequest $companyUserRequest
     */
    public function __construct(CompanyUserRequest $companyUserRequest)
    {
        $this->companyUserRequest = $companyUserRequest;
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
        $companyUserRequest = $this->companyUserRequest;
        $company = $companyUserRequest->company;
        $url = route('company.requests.index', [ 'company' => $company->domain_name ]);

        return (new MailMessage)
            ->subject($companyUserRequest->full_name . ' has requested to be added to your company on ' . config('app.name'))
            ->line('Please login to ' . config('app.name') . ' to approve/reject the user')
            ->action('Sign In', $url)
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
            //
        ];
    }
}
