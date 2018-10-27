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
                <h3>Settings</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav")
            </div>
            <div class="col s12 m9 xl10">
                <form id="edit-company-settings" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="tax" name="tax" type="number" data-parsley-trigger="change" value="{{ $companysettings->tax ?? '' }}" placeholder="Company Tax %">
                                <label for="tax" class="label-validation">Company Tax %</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="invoice_prefix" name="invoice_prefix" type="text" data-parsley-trigger="change" data-parsley-minlength="2" value="{{ $companysettings->invoice_prefix ?? '' }}" placeholder="Invoice Prefix">
                                <label for="invoice_prefix" class="label-validation">Invoice Prefix</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="quote_prefix" name="quote_prefix" type="text" data-parsley-trigger="change" data-parsley-minlength="2" value="{{ $companysettings->quote_prefix ?? '' }}" placeholder="Quote Prefix">
                                <label for="quote_prefix" class="label-validation">Quote Prefix</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="invoice_conditions" name="invoice_conditions" class="trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Invoice Conditions">@if(isset($companysettings->invoice_conditions)){!! $companysettings->invoice_conditions !!}@else @endif</textarea>
                                <label for="invoice_conditions" class="label-validation">Invoice Conditions</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="quote_conditions" name="quote_conditions" class="trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Quote Conditions">@if(isset($companysettings->quote_conditions)){!! $companysettings->quote_conditions !!}@else @endif</textarea>
                                <label for="quote_conditions" class="label-validation">Quote Conditions</label>
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
    <script type="text/javascript">
        "use strict";
        $(function() {
            $('.trumbowyg-textarea').trumbowyg({
                svgPath: '/assets/fonts/trumbowygicons.svg',
                removeformatPasted: true,
                resetCss: true,
                autogrow: true,
            });

            $('#edit-company-settings').parsley({
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
                    if (velem.$element.is('#phone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                    }
                })
                .on('field:error', function(velem) {
                    if (velem.$element.is('#phone'))
                    {
                        velem.$element.parent('').siblings('span.helper-text').removeClass('valid').addClass('invalid');
                        velem.$element.parent('').siblings('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                });
        });
    </script>
@stop