@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Create Invoice</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="create-invoice" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="nice_invoice_id" name="nice_invoice_id" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $invoicenumber ?? '' }}">
                                <label for="nice_invoice_id" class="label-validation">Invoice ID</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="date" name="date" class="datepicker" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('date') }}" placeholder="Date">
                                <label for="date" class="label-validation">Date</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="netdays" name="netdays" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('netdays') }}" placeholder="Net Days">
                                <label for="netdays" class="label-validation">Net Days</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="companyname" name="companyname" type="text" data-parsley-required="true"  data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('companyname') }}" placeholder="Client Company Name">
                                <label for="companyname" class="label-validation">Client Company Name</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="nickname" name="nickname" type="text" data-parsley-trigger="change" value="{{ old('nickname') }}" placeholder="Client Nickname">
                                <label for="nickname" class="label-validation">Client Nickname</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select id="country_code" name="country_code" data-parsley-trigger="change" placeholder="Client Country">
                                    <option disabled="" selected="selected" value="">Client Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country['iso_3166_1_alpha2'] }}" @if(old('country_code') == $country['iso_3166_1_alpha2']) selected @endif>{{ $country['name']['common'] }}</option>
                                    @endforeach
                                </select>
                                <label for="country" class="label-validation">Client Country</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="cphone" name="cphone" type="text" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#cphone" value="{{ old('phone') }}">
                                <input id="phone" name="phone" class="form-control" type="hidden" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$">
                                <label for="cphone" class="manual-validation">Phone</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="crn" name="crn" type="text" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ old('crn') }}" placeholder="Client Company Registration Number">
                                <label for="crn" class="label-validation">Client Company Registration Number</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="website" name="website" type="text" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('website') }}" placeholder="Client Website">
                                <label for="website" class="label-validation">Client Website</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <h5>Address</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="block" name="block" type="text" data-parsley-trigger="change" value="{{ old('block') }}" placeholder="Client Block">
                                <label for="block" class="label-validation">Client Block</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="street" name="street" type="text" data-parsley-trigger="change" value="{{ old('street') }}" placeholder="Client Street">
                                <label for="street" class="label-validation">Client Street</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <i class="mdi mdi-pound prefix-inline"></i>
                                <input id="unitnumber" name="unitnumber" type="text" data-parsley-trigger="change" value="{{ old('unitnumber') }}" placeholder="Client Unit Number">
                                <label for="unitnumber" class="label-validation">Client Unit Number</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="postalcode" name="postalcode" type="text" data-parsley-trigger="change" value="{{ old('postalcode') }}" placeholder="Client Postal Code">
                                <label for="postalcode" class="label-validation">Client Postal Code</label>
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
                                    <option value="mr" @if(old('contactsalutation') == "mr") selected @endif>Mr.</option>
                                    <option value="mrs" @if(old('contactsalutation') == "mrs") selected @endif>Mrs.</option>
                                    <option value="mdm" @if(old('contactsalutation') == "mdm") selected @endif>Mdm.</option>
                                    <option value="miss" @if(old('contactsalutation') == "miss") selected @endif>Miss.</option>
                                </select>
                                <label for="contactsalutation" class="label-validation">Contact Salutation</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="contactfirstname" name="contactfirstname" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('contactfirstname') }}" placeholder="Client Contact First Name">
                                <label for="contactfirstname" class="label-validation">Contact First Name</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input id="contactlastname" name="contactlastname" type="text" data-parsley-trigger="change" value="{{ old('contactlastname') }}" placeholder="Client Contact Last Name">
                                <label for="contactlastname" class="label-validation">Contact Last Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="fphone" name="fphone" type="text" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#fphone" value="{{ old('contactphone') }}">
                                <input id="contactphone" name="contactphone" class="form-control" type="hidden" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$">
                                <label for="fphone" class="manual-validation">Phone</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="contactemail" name="contactemail" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('contactemail') }}" placeholder="Client Contact Email">
                                <label for="contactemail" class="label-validation">Contact Email</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <h3>Items</h3>
                        </div>
                        <div class="col s6">
                            <a id="invoice-item-add" class="btn-floating btn-large waves-effect waves-light red right"><i class="material-icons">add</i></a>
                        </div>
                    </div>
                    <div id="invoice-items-container">
                        <div id="invoice_item_0" class="card-panel">
                            <div class="row">
                                <div class="input-field col s8">
                                    <input id="item_name" name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_name') }}" placeholder="Item Name">
                                    <label for="item_name" class="label-validation">Name</label>
                                </div>
                                <div class="input-field col s2">
                                    <input id="item_quantity" name="item_quantity[]" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_quantity') }}" placeholder="Item Quantity">
                                    <label for="item_quantity" class="label-validation">Quantity</label>
                                </div>
                                <div class="input-field col s2">
                                    <input id="item_price" name="item_price[]" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_price') }}" placeholder="Item Price">
                                    <label for="item_price" class="label-validation">Price</label>
                                </div>
                                <div class="input-field col s12">
                                    <textarea id="item_description" name="item_description[]" class="trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description">{{ old('item_description') }}</textarea>
                                    <label for="item_description" class="label-validation">Description</label>
                                </div>
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
    <script type="text/javascript">
        "use strict";
        $(function() {
            var invoiceitemcount = 0;

            $('.trumbowyg-textarea').trumbowyg({
                removeformatPasted: true
            });

            $('.datepicker').pickadate({
                formatSubmit: 'yyyy-mm-dd',
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year,
                today: 'Today',
                clear: 'Clear',
                close: 'Ok',
                closeOnSelect: true // Close upon selecting a date,
            });

            $('#client_id').selectize();

            $('#invoice-item-add').on('click', function() {
                initInvoiceItem(++invoiceitemcount, 'invoice-items-container');
            });

            function initInvoiceItem(count, elementid) {
                var invoiceitem = '<div id="invoice_item_' + count + '" class="card-panel"> <div class="row"> <div class="input-field col s8"> <input id="item_name" name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_name" class="label-validation">Name</label> </div> <div class="input-field col s2"> <input id="item_quantity" name="item_quantity[]" type="number" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_quantity" class="label-validation">Quantity</label> </div> <div class="input-field col s2"> <input id="item_price" name="item_price[]" type="number" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_price" class="label-validation">Price</label> </div> <div class="input-field col s12"> <textarea id="item_description" name="item_description[]" class="trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description"></textarea> <label for="item_description" class="label-validation">Description</label> </div> </div> </div>';
                $('#' + elementid).append(invoiceitem);
                $('.trumbowyg-textarea').trumbowyg({
                    removeformatPasted: true
                });
            }

            $('#country_code').selectize({});
            $('#contactsalutation').selectize({});

            $("#fphone").intlTelInput({
                initialCountry: "sg",
                utilsScript: "/assets/js/utils.js"
            });

            $("#cphone").intlTelInput({
                initialCountry: "sg",
                utilsScript: "/assets/js/utils.js"
            });

            $( "#fphone" ).focusin(function() {
                $(this).parent().siblings('.manual-validation').addClass('black-text');
            });

            $( "#fphone" ).focusout(function() {
                $(this).parent().siblings('.manual-validation').removeClass('black-text');
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

            $('#create-invoice').parsley({
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
                    if (velem.$element.is('select')) {
                        velem.$element.siblings('.selectize-control').removeClass('invalid').addClass('valid');
                        //velem.$element.parent('.select-wrapper').removeClass('invalid').addClass('valid');
                        //velem.$element.siblings('.select-dropdown').removeClass('invalid').addClass('valid');
                    } else if (velem.$element.is(':radio'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                    }
                    else if (velem.$element.is('#fphone') || velem.$element.is('#cphone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                    }
                })
                .on('field:error', function(velem) {
                    if (velem.$element.is('select')) {
                        velem.$element.siblings('.selectize-control').removeClass('valid').addClass('invalid');

                        //velem.$element.parent('.select-wrapper').removeClass('valid').addClass('invalid');
                        //velem.$element.siblings('.select-dropdown').removeClass('valid').addClass('invalid');
                        //velem.$element.parent('.select-wrapper').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    } else if (velem.$element.is(':radio'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                        velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                    else if (velem.$element.is('#fphone') || velem.$element.is('#cphone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                        velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                })
                .on('form:submit', function(velem) {
                    $("#phone").val($("#cphone").intlTelInput("getNumber"));
                    $("#contactphone").val($("#fphone").intlTelInput("getNumber"));
                });
        });
    </script>
@stop