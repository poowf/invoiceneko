@extends("layouts.default", ['page_title' => 'Company | Invite | Join'])

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
                    <hr class="mbth30">
                    <div class="form-box">
                        <h6 class="mbtm30">Join {{ $companyInvite->company->name }} Company</h6>
                        <p class="mbtm30">
                            You have been invited to join a company, click the button below to accept
                        </p>
                        <form id="company-join-form" method="post">
                            {{ csrf_field() }}
                            <button id="company-join-btn" class="btn btn-link waves-effect waves-light full-width" type="submit" name="action">Join</button>
                        </form>
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
            Unicorn.initParsleyValidation('#company-join-form');
        });
    </script>
@stop