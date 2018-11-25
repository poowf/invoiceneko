@component('mail::message')
    # Contact Form

    The body of your message.

    Name: {{ $name }}
    Email: {{ $email }}
    Message: {{ $message }}

    @component('mail::button', ['url' => 'mailto:' . $email])
        Email Sender
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent