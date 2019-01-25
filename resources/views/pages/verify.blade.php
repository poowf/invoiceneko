@extends("layouts.default", ['page_title' => 'Invoice Neko | Verify Your Email Address'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="wrap v-center">
        <div class="container content-main-authentication">
            <div class="login-container">
                <div class="card-panel pall30">
                    <div class="avatar">
                        <img src="{{ asset('assets/img/avatar.png') }}" width="150" height="150">
                    </div>
                    <hr><br>
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
