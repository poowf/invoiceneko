@extends("layouts.default", ['page_title' => 'Company | Request Access'])

@section("head")
    <link href="{{ mix('/assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css">

    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Request for an account</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="request-access" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="full_name" name="full_name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('full_name') }}" placeholder="Name">
                                <label for="full_name" class="label-validation">Full Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row pbtm20">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="tel" class="phone-input" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ old('phone') }}">
                                <label for="phone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('email') }}" placeholder="Email">
                                <label for="email" class="label-validation">Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Request Access</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript" src="{{ mix('/assets/js/intlTelInput.js') }}"></script>

    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initPhoneInput('#phone');
            Unicorn.initParsleyValidation('#request-access');
        });
    </script>
@stop