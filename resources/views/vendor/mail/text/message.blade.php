@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
            <a href="{{ route('privacy') }}" target="_blank" rel="nofollow noopener noreferrer">Privacy Policy</a>
            <a href="{{ route('terms') }}" target="_blank" rel="nofollow noopener noreferrer">Terms & Conditions</a>
        @endcomponent
    @endslot
@endcomponent
