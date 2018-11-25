@component('mail::message')
# Contact Form

Name: {{ $name }}

Email: {{ $email }}

Message: {{ $message }}

@component('mail::button', ['url' => 'mailto:' . $email])
Email Sender
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent