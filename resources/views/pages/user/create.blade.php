@extends("layouts.default", ['page_title' => 'User | Create'])

@section("head")
    <link href="{{ mix('/assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Sign Up</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="signup" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" name="username" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ old('username') }}" placeholder="Username">
                                <label for="username" class="label-validation">Username</label>
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
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" name="password" type="password" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="6" placeholder="Password">
                                <label for="password" class="label-validation">Password</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password_confirmation" name="password_confirmation" type="password" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="6" data-parsley-equalto="#password" placeholder="Confirm Password">
                                <label for="password" class="label-validation">Password Confirmation</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="full_name" name="full_name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('full_name') }}" placeholder="Name">
                                <label for="full_name" class="label-validation">Full Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="country_code" name="country_code" data-parsley-required="false" data-parsley-trigger="change" placeholder="Country">
                                    @foreach($countries as $country)
                                        <option value="{{ $country['iso_3166_1_alpha2'] }}" @if(old('country_code') == $country['iso_3166_1_alpha2']) selected @endif>{{ $country['name']['common'] }}</option>
                                    @endforeach
                                </select>
                                <label for="country_code" class="label-validation">Country</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="timezone" name="timezone" data-parsley-required="false" data-parsley-trigger="change">
                                    <option disabled="" selected="selected" value="">Timezone</option>
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone }}" @if(old('timezone') == $timezone) selected @endif> {{ $timezone }}</option>
                                    @endforeach
                                </select>
                                <label for="timezone" class="label-validation">Timezone</label>
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
                            <div class="radio-field col s12 left">
                                <label id="rbtn-label" class="rbtn-label" for="gender">Gender</label>
                                <p class="rbtn">
                                    <label for="gender-male">
                                        <input id="gender-male" name="gender" type="radio" value="male" data-parsley-required="true" data-parsley-trigger="change" @if(old('gender') == "male") checked @endif>
                                        <span>Male</span>
                                    </label>
                                </p>
                                <p class="rbtn">
                                    <label for="gender-female">
                                        <input id="gender-female" name="gender" type="radio" value="female" @if(old('gender') == "female") checked @endif>
                                        <span>Female</span>
                                    </label>
                                </p>
                                <span class="helper-text manual-validation"></span>
                            </div>
                        </div>
                        @if(app('request')->has('hasinvite'))
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="companyinvite" name="companyinvite" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('companyinvite') }}" placeholder="Invite Code">
                                <label for="companyinvite" class="label-validation">Invite Code</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">@if(app('request')->has('token') || app('request')->has('hasinvite')) Create @else Next @endif</button>
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
            $('#country_code').selectize({});
            $('#timezone').selectize({});
            Unicorn.initPhoneInput('#phone');
            Unicorn.initParsleyValidation('#signup');
        });
    </script>
@stop