@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
        .logo-display-container, .smlogo-display-container {
            display: inline-block;
        }

        .logo-display-container img, .smlogo-display-container img {
            max-height: 100px;
            max-width: 250px;
            margin-top: 15px;

            object-fit: cover;
            object-position: center right;
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

        .logo-display-container:hover span.text-content, .smlogo-display-container:hover span.text-content {
            opacity: 1;
        }
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
                                <textarea id="invoice_conditions" name="invoice_conditions" class="trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Invoice Conditions">{!! $companysettings->invoice_conditions !!}</textarea>
                                <label for="name" class="label-validation">Invoice Conditions</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m2 offset-m10" type="submit" name="action">Update</button>
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
            $('.trumbowyg-textarea').trumbowyg();

            $('#edit-company-settings').parsley({
                successClass: 'valid',
                errorClass: 'invalid',
                errorsContainer: function (velem) {
                    var $errelem = velem.$element.siblings('label');
                    $errelem.attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    return true;
                },
                errorsWrapper: '',
                errorTemplate: ''
            })
                .on('field:validated', function(velem) {

                })
                .on('field:success', function(velem) {
                    if (velem.$element.is('#fphone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('invalid').addClass('valid');
                    }
                })
                .on('field:error', function(velem) {
                    if (velem.$element.is('#fphone'))
                    {
                        velem.$element.parent('').siblings('label').removeClass('valid').addClass('invalid');
                        velem.$element.parent('').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                })
                .on('form:submit', function(velem) {
                    $("#phone").val($("#fphone").intlTelInput("getNumber"));
                });
        });
    </script>
@stop