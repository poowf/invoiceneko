@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <link href="{{ mix('/assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css">

    <style>
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
                            <div class="input-field col s12 m6">
                                <input id="companyname" name="companyname" type="text" data-parsley-required="true"  data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $client->companyname or '' }}" placeholder="Client Company Name">
                                <label for="companyname" class="label-validation">Client Company Name</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="nickname" name="nickname" type="text" data-parsley-trigger="change" value="{{ $client->nickname or '' }}" placeholder="Client Nickname">
                                <label for="nickname" class="label-validation">Client Nickname</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select id="country" name="country" data-parsley-trigger="change">
                                    <option disabled="" selected="selected" value="">Client Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country['name'] }}" @if($client->country == $country['name']) selected @endif> {{ $country['name'] }}</option>
                                    @endforeach
                                </select>
                                <label for="country" class="label-validation">Client Country</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="phone" name="phone" type="text" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ $client->phone or '' }}">
                                <label for="phone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="crn" name="crn" type="text" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $client->crn or '' }}" placeholder="Client Company Registration Number">
                                <label for="crn" class="label-validation">Client Company Registration Number</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6">
                                <input id="website" name="website" type="text" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $client->website or '' }}" placeholder="Client Website">
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
                                <input id="block" name="block" type="text" data-parsley-trigger="change" value="{{ $client->block or '' }}" placeholder="Client Block">
                                <label for="block" class="label-validation">Client Block</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6">
                                <input id="street" name="street" type="text" data-parsley-trigger="change" value="{{ $client->street or '' }}" placeholder="Client Street">
                                <label for="street" class="label-validation">Client Street</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <i class="mdi mdi-pound prefix-inline"></i>
                                <input id="unitnumber" name="unitnumber" type="text" data-parsley-trigger="change" value="{{ $client->unitnumber or '' }}" placeholder="Client Unit Number">
                                <label for="unitnumber" class="label-validation">Client Unit Number</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6">
                                <input id="postalcode" name="postalcode" type="text" data-parsley-trigger="change" value="{{ $client->postalcode or '' }}" placeholder="Client Postal Code">
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
                                </select>
                                <label for="contactsalutation" class="label-validation">Contact Salutation</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="contactfirstname" name="contactfirstname" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $client->contactfirstname or '' }}" placeholder="Client Contact First Name">
                                <label for="contactfirstname" class="label-validation">Contact First Name</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="contactlastname" name="contactlastname" type="text" data-parsley-trigger="change" value="{{ $client->contactlastname or '' }}" placeholder="Client Contact Last Name">
                                <label for="contactlastname" class="label-validation">Contact Last Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="contactphone" name="contactphone" type="text" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#contactphone" value="{{ $client->contactphone or '' }}">
                                <label for="contactphone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="contactemail" name="contactemail" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ $client->contactemail or '' }}" placeholder="Client Contact Email">
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
            $('#country').selectize({});
            $('#contactsalutation').selectize({});

            $("#contactphone").intlTelInput({
                initialCountry: "sg",
                utilsScript: "/assets/js/utils.js"
            });

            $("#phone").intlTelInput({
                initialCountry: "sg",
                utilsScript: "/assets/js/utils.js"
            });

            $( "#phone" ).focusin(function() {
                $(this).parent().siblings('.label-validation').addClass('theme-text');
            });

            $( "#phone" ).focusout(function() {
                $(this).parent().siblings('.label-validation').removeClass('theme-text');
            });

            window.Parsley
                .addValidator('phoneFormat', {
                    requirementType: 'string',
                    validateString: function(value, elementid) {
                        if($(elementid).intlTelInput("isValidNumber"))
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    },
                    messages: {
                        en: 'This is an invalid phone number format'
                    }
                });

            $('#edit-client').parsley({
                successClass: 'valid',
                errorClass: 'invalid',
                errorsContainer: function (velem) {
                    let $errelem = velem.$element.siblings('span.helper-text');
                    $errelem.attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    return true;
                },
                errorsWrapper: '',
                errorTemplate: ''
            })
                .on('field:validated', function(velem) {

                })
                .on('field:success', function(velem) {
                    if (velem.$element.is(':radio'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                    }
                    else if (velem.$element.is('#contactphone') || velem.$element.is('#phone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                    }
                })
                .on('field:error', function(velem) {
                    if (velem.$element.is(':radio'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                        velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                    else if (velem.$element.is('#contactphone') || velem.$element.is('#phone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                        velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                })
                .on('form:submit', function(velem) {
                });
        });
    </script>
@stop