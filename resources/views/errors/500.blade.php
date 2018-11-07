@extends("layouts/error")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
        .sentry-error-embed .form-submit .btn {
            height: initial;
            line-height: initial;
        }
    </style>
@stop

@section("content")
    <div class="container">
        <div class="col l12 m12 s12 center-align">
            <div class="v-wrap">
                <div class="error-container v-center">
                    <h2>Error 500 - Server Error</h2>
                    @if(app()->bound('sentry') && !empty(Sentry::getLastEventID()))
                        <div class="error-description">Error ID: {{ Sentry::getLastEventID() }}</div>


                        <!-- Sentry JS SDK 2.1.+ required -->
                        <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

                        @if(auth()->check())
                            @if($user = auth()->user())
                                <script>
                                    Raven.showReportDialog({
                                        eventId: '{{ Sentry::getLastEventID() }}',
                                        // use the public DSN (dont include your secret!)
                                        dsn: '{{ env('SENTRY_LARAVEL_DSN') }}',
                                        user: {
                                            'name': '{{ $user->full_name ?? '' }}',
                                            'email': '{{ $user->email ?? '' }}',
                                        }
                                    });
                                </script>
                            @endif
                        @endif
                    @endif
                    <div class="error-description">A catastrophic failure has happened, we are on it!</div>
                    <div class="error-goback-text">Would you like to go home?</div>
                    <div class="error-goback-button"><a href="{{ route('main') }}" class="btn btn-xl btn-primary btn-link">Let's go
                            home</a></div>
                    <div class="footer">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")

    <script type="text/javascript">
        "use strict";
        $(function() {
        });
    </script>
@stop