@extends("layouts.default", ['page_title' => 'Company | Create'])

@section("head")
    <link href="{{ mix('/assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Create Company</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="create-company" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" name="name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('name') }}" placeholder="Company Name">
                                <label for="name" class="label-validation">Company Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="crn" name="crn" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ old('crn') }}" placeholder="Company Registration Number">
                                <label for="crn" class="label-validation">Company Registration Number</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="country_code" name="country_code" data-parsley-trigger="change" placeholder="Country">
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
                                <select id="timezone" name="timezone" data-parsley-trigger="change">
                                    <option disabled="" selected="selected" value="">Timezone</option>
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone }}" @if(old('timezone') == $timezone) selected @endif> {{ $timezone }}</option>
                                    @endforeach
                                </select>
                                <label for="timezone" class="label-validation">Timezone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="domain_name" name="domain_name" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('domain_name') }}" placeholder="Company Domain Name">
                                <label for="domain_name" class="label-validation">Company Domain Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('email') }}" placeholder="Company Email">
                                <label for="email" class="label-validation">Company Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row pbtm20">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="tel" class="phone-input" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ old('phone') }}">
                                <label for="phone" class="label-validation">Company Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <div class="file-field input-field">
                                    <div class="btn btn-link tooltipped" data-position="top" data-delay="50" data-tooltip="Recommended Size: 420 (W) x 220 (H) with White Background (Optional)">
                                        <span>File</span>
                                        <input id="logo" name="logo" type="file" accept="image/*" data-maxsize="10M"/>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="logofp" name="logofp" class="file-path validate" type="text" data-parsley-required="false" data-parsley-fileuploaded="true" data-parsley-trigger="change" placeholder="Company Logo"/>
                                    </div>
                                </div>
                                <label for="logo" class="label-validation">
                                    Logo
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <div class="file-field input-field">
                                    <div class="btn btn-link tooltipped" data-position="top" data-delay="50" data-tooltip="Recommended Size: 200 (W) x 200 (H) with White Background (Optional)">
                                        <span>File</span>
                                        <input id="smlogo" name="smlogo" type="file" accept="image/*" data-maxsize="10M"/>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="smlogofp" name="smlogofp" class="file-path validate" type="text" data-parsley-required="false" data-parsley-fileuploaded="true" data-parsley-trigger="change" placeholder="Small Company Logo"/>
                                    </div>
                                </div>
                                <label for="smlogo" class="label-validation">
                                    Small Logo
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Create</button>
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
            Unicorn.initParsleyValidation('#create-company');
        });
    </script>
@stop