@extends("layouts.default", ['page_title' => 'Company | Edit'])

@section("head")
    <link href="{{ asset(mix('/assets/css/intlTelInput.css')) }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(mix('/assets/css/selectize.css')) }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Details</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav-company")
            </div>
            <div class="col s12 m9 xl10">
                <form id="edit-company" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="logo-container input-field col s12">
                                <label for="logo-display" class="label-validation">Logo</label>
                                <div class="logo-display-container company-logo tooltipped" data-position="top" data-tooltip="Recommended Size: 420 (W) x 220 (H) with White Background (Optional)">
                                    <img id="logo-display" src="@if($company){{ \App\Library\Poowf\Unicorn::getStorageFile($company->logo, [420, 220]) }}@else{!! 'https://via.placeholder.com/420x220' !!}@endif">
                                    <span class="text-content"><span id="logo-upload">Change?</span></span>
                                </div>
                                <input id="logo" name="logo" type="file" accept="image/*" style="display: none;" data-maxsize="10M">
                            </div>
                        </div>
                        <div class="row">
                            <div class="smlogo-container input-field col s12">
                                <label for="smlogo-display" class="label-validation">Small Logo</label>
                                <div class="smlogo-display-container company-logo tooltipped" data-position="top" data-tooltip="Recommended Size: 200 (W) x 200 (H) with White Background (Optional)">
                                    <img id="smlogo-display" src="@if($company){{ \App\Library\Poowf\Unicorn::getStorageFile($company->smlogo, [200,200]) }}@else{!! 'https://via.placeholder.com/200x200' !!}@endif"  height="100">
                                    <span class="text-content"><span id="smlogo-upload">Change?</span></span>
                                </div>
                                <input id="smlogo" name="smlogo" type="file" accept="image/*" style="display: none;" data-maxsize="10M">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" name="name" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $company->name ?? '' }}" placeholder="Company Name">
                                <label for="name" class="label-validation">Company Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="crn" name="crn" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $company->crn ?? '' }}" placeholder="Company Registration Number">
                                <label for="crn" class="label-validation">Company Registration Number</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="country_code" name="country_code" data-parsley-trigger="change" placeholder="Country">
                                    <option disabled="" selected="selected" value="">Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country['iso_3166_1_alpha2'] }}" @if($company) @if($company->country_code == $country['iso_3166_1_alpha2']) selected @endif @endif>{{ $country['name'] }}</option>
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
                                        <option value="{{ $timezone }}" @if($company) @if($company->timezone == $timezone) selected @endif @endif> {{ $timezone }}</option>
                                    @endforeach
                                </select>
                                <label for="timezone" class="label-validation">Timezone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="domain_name" name="domain_name" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $company->domain_name ?? '' }}" placeholder="Company Domain Name">
                                <label for="domain_name" class="label-validation">Company Domain Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ $company->email ?? '' }}" placeholder="Company Email">
                                <label for="email" class="label-validation">Company Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row pbtm20">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="tel" class="phone-input" data-parsley-required="true" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ $company->phone ?? '' }}">
                                <label for="phone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript" src="{{ asset(mix('/assets/js/intlTelInput.js')) }}"></script>

    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initSelectize('#country_code');
            Unicorn.initSelectize('#timezone');
            Unicorn.initPhoneInput('#phone');
            Unicorn.initParsleyValidation('#edit-company');
            Unicorn.initImageUpload('#logo', '#logo-upload','#logo-display');
            Unicorn.initImageUpload('#smlogo', '#smlogo-upload','#smlogo-display');
        });
    </script>
@stop