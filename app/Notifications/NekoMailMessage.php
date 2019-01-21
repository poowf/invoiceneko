<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class NekoMailMessage extends MailMessage
{
    /**
     * The notification's data.
     *
     * @var string|null
     */
    public $viewData;

    /**
     * Set the content of the notification.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->viewData['content'] = $content;

        return $this;
    }

    /**
     * Get the data array for the mail message.
     *
     * @return array
     */
    public function data()
    {
        return array_merge($this->toArray(), $this->viewData);
    }
}