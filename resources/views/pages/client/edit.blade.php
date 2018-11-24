@extends("layouts.default", ['page_title' => 'Client | Edit'])

@section("head")
    <link href="{{ mix('/assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/selectize.css') }}" rel="stylesheet" type="text/css">

    <style>
        .logo-display-container {
            display: inline-block;
        }

        .logo-display-container img {
            margin-top: 15px;
            object-fit: cover;
            object-position: center right;
        }

        .logo-display-container img {
            width: 250px;
            height: 250px;
        }

        .logo-display-container span.text-content {
            width: 250px;
        }

        span.text-content {
            width: 300px;
            padding: 10px 0;
            margin-top: 15px;
            background: rgba(0,0,0,0.5);
            color: white;
            cursor: pointer;
            display: table;
            position: absolute;
            top: 0;
            opacity: 0;
            -webkit-transition: opacity 500ms;
            -moz-transition: opacity 500ms;
            -o-transition: opacity 500ms;
            transition: opacity 500ms;
        }

        span.text-content span {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .logo-display-container:hover span.text-content {
            opacity: 1;
        }
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Edit Client</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="edit-client" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="col s12">
                                <h5>Details</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="logo-container input-field col s12">
                                <label for="logo-display" class="label-validation">Logo</label>
                                <div class="logo-display-container tooltipped" data-position="top" data-tooltip="Recommended Size: 500 (W) x 500 (H) with White Background (Optional)">
                                    <img id="logo-display" src="@if($client){{ \App\Library\Poowf\Unicorn::getStorageFile($client->logo, [500, 500]) }}@else{!! '//via.placeholder.com/500x500' !!}@endif">
                                    <span class="text-content"><span id="logo-upload">Change?</span></span>
                                </div>
                                <input id="logo" name="logo" type="file" accept="image/*" style="display: none;" data-maxsize="10M">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="companyname" name="companyname" type="text" data-parsley-required="true"  data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $client->companyname ?? '' }}" placeholder="Client Company Name">
                                <label for="companyname" class="label-validation">Client Company Name</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="nickname" name="nickname" type="text" data-parsley-trigger="change" value="{{ $client->nickname ?? '' }}" placeholder="Client Nickname">
                                <label for="nickname" class="label-validation">Client Nickname</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select id="country_code" name="country_code" data-parsley-trigger="change" placeholder="Client Country">
                                    @foreach($countries as $country)
                                        <option value="{{ $country['iso_3166_1_alpha2'] }}" @if($client->country_code == $country['iso_3166_1_alpha2']) selected @endif>{{ $country['name']['common'] }}</option>
                                    @endforeach
                                </select>
                                <label for="country" class="label-validation">Client Country</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="phone" name="phone" type="tel" class="phone-input" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ $client->phone ?? '' }}">
                                <label for="phone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="crn" name="crn" type="text" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $client->crn ?? '' }}" placeholder="Client Company Registration Number">
                                <label for="crn" class="label-validation">Client Company Registration Number</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="website" name="website" type="text" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $client->website ?? '' }}" placeholder="Client Website">
                                <label for="website" class="label-validation">Client Website</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <h5>Address</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="block" name="block" type="text" data-parsley-trigger="change" value="{{ $client->block ?? '' }}" placeholder="Client Block">
                                <label for="block" class="label-validation">Client Block</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6">
                                <input id="street" name="street" type="text" data-parsley-trigger="change" value="{{ $client->street ?? '' }}" placeholder="Client Street">
                                <label for="street" class="label-validation">Client Street</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <i class="mdi mdi-pound prefix-inline"></i>
                                <input id="unitnumber" name="unitnumber" type="text" data-parsley-trigger="change" value="{{ $client->unitnumber ?? '' }}" placeholder="Client Unit Number">
                                <label for="unitnumber" class="label-validation">Client Unit Number</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6">
                                <input id="postalcode" name="postalcode" type="text" data-parsley-trigger="change" value="{{ $client->postalcode ?? '' }}" placeholder="Client Postal Code">
                                <label for="postalcode" class="label-validation">Client Postal Code</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <h5>Contact</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m2">
                                <select id="contactsalutation" name="contactsalutation" data-parsley-trigger="change">
                                    <option disabled="" selected="selected" value="">Client Contact Salutation</option>
                                    <option value="mr" @if($client->contactsalutation == "mr") selected @endif>Mr.</option>
                                    <option value="mrs" @if($client->contactsalutation == "mrs") selected @endif>Mrs.</option>
                                    <option value="mdm" @if($client->contactsalutation == "mdm") selected @endif>Mdm.</option>
                                    <option value="miss" @if($client->contactsalutation == "miss") selected @endif>Miss.</option>
                                    <option value="miss" @if($client->contactsalutation == "dr") selected @endif>Dr.</option>
                                    <option value="miss" @if($client->contactsalutation == "prof") selected @endif>Prof.</option>
                                    <option value="miss" @if($client->contactsalutation == "mx") selected @endif>Mx.</option>
                                </select>
                                <label for="contactsalutation" class="label-validation">Contact Salutation</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="contactfirstname" name="contactfirstname" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $client->contactfirstname ?? '' }}" placeholder="Client Contact First Name">
                                <label for="contactfirstname" class="label-validation">Contact First Name</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="contactlastname" name="contactlastname" type="text" data-parsley-trigger="change" value="{{ $client->contactlastname ?? '' }}" placeholder="Client Contact Last Name">
                                <label for="contactlastname" class="label-validation">Contact Last Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="contactphone" name="contactphone" type="tel" class="phone-input" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#contactphone" value="{{ $client->contactphone ?? '' }}">
                                <label for="contactphone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="contactemail" name="contactemail" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ $client->contactemail ?? '' }}" placeholder="Client Contact Email">
                                <label for="contactemail" class="label-validation">Contact Email</label>
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
    <script type="text/javascript" src="{{ mix('/assets/js/intlTelInput.js') }}"></script>

    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initSelectize('#country_code');
            Unicorn.initSelectize('#contactsalutation');
            Unicorn.initPhoneInput('#phone');
            Unicorn.initPhoneInput('#contactphone');
            Unicorn.initParsleyValidation('#edit-client');
            Unicorn.initImageUpload('#logo', '#logo-upload','#logo-display');
        });
    </script>
@stop