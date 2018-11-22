<?php

namespace App\Notifications;

use App\Models\Company;
use App\Models\CompanyInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InviteUserNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $companyInvite;

    /**
     * Create a new notification instance.
     *
     * @param CompanyInvite $companyInvite
     */
    public function __construct(CompanyInvite $companyInvite)
    {
        $this->companyInvite = $companyInvite;
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
        $companyInvite = $this->companyInvite;
        $company = $companyInvite->company;
        $url = route('company.invite.show', [ 'companyinvite' => $companyInvite->token ]);

        return (new MailMessage)
            ->subject('You have been invited to join ' . $company->name . ' on ' . config('app.name'))
            ->line('Join ' . $company->name . ' on ' . config('app.name'))
            ->action('Accept Invite', $url)
            ->line('Your invite code is : ' . $companyInvite->token)
            ->line('This special invite link expires in 48 hours')
            ->line('Please contact the company owner for a new invite link if it has expired')
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
