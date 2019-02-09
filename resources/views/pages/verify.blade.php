@extends("layouts.default", ['page_title' => 'Verify Your Email Address'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="wrap v-center">
        <div class="container content-main-authentication">
            <div class="login-container">
                <div class="card-panel pall30">
                    <div class="hero-logo-container circle">
                        <img src="{{ asset('assets/img/logo.svg') }}" class="hero-logo-image">
                    </div>
                    <hr class="mtop20"><br>
                    <div class="form-box">
                        <h6>Verify your email address</h6>
                        @if (session('resent'))
                            <div role="alert">
                                {{ 'A fresh verification link has been sent to your email address.' }}
                            </div>
                        @endif
                        <p>
                            Didn't receive an email?
                            <a href="{{ route('verification.resend') }}" class="btn btn-link mtop10">Resend Verification Email</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script>
        "use strict";
        $(function() {
        });
    </script>
@stop
