@extends("layouts.default", ['page_title' => 'Recipient | Edit'])

@section("head")
    <link href="{{ mix('/assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/selectize.css') }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Edit Recipient</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="edit-recipient" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="salutation" name="salutation" data-parsley-required="true" data-parsley-trigger="change">
                                    <option disabled="" selected="selected" value="">Salutation</option>
                                    <option value="mr" @if($recipient->salutation == "mr") selected @endif>Mr.</option>
                                    <option value="mrs" @if($recipient->salutation == "mrs") selected @endif>Mrs.</option>
                                    <option value="mdm" @if($recipient->salutation == "mdm") selected @endif>Mdm.</option>
                                    <option value="miss" @if($recipient->salutation == "miss") selected @endif>Miss.</option>
                                    <option value="dr" @if($recipient->salutation == "dr") selected @endif>Dr.</option>
                                    <option value="prof" @if($recipient->salutation == "prof") selected @endif>Prof.</option>
                                    <option value="mx" @if($recipient->salutation == "mx") selected @endif>Mx.</option>
                                </select>
                                <label for="salutation" class="label-validation">Salutation</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="first_name" name="first_name" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $recipient->first_name ?? '' }}" placeholder="Recipient First Name">
                                <label for="first_name" class="label-validation">First Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="last_name" name="last_name" type="text" data-parsley-trigger="change" value="{{ $recipient->last_name ?? '' }}" placeholder="Recipient Last Name">
                                <label for="last_name" class="label-validation">Last Name</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="tel" class="phone-input" data-parsley-trigger="change" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" data-parsley-phone-format="#phone" value="{{ $recipient->phone ?? '' }}">
                                <label for="phone" class="label-validation">Phone</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" data-parsley-required="true" data-parsley-trigger="change" value="{{ $recipient->email ?? '' }}" placeholder="Recipient Email">
                                <label for="email" class="label-validation">Email</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <button class="btn btn-link waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Update</button>
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
            Unicorn.initSelectize('#salutation');
            Unicorn.initPhoneInput('#phone');
            Unicorn.initParsleyValidation('#edit-recipient');
        });
    </script>
@stop