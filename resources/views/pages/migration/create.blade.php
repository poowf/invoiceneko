@extends("layouts.default", ['page_title' => 'pagetitle'])

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
        .error-container .card-panel {
            max-height: 150px;
            overflow: hidden;
            -webkit-transition: max-height 0.5s;
            -moz-transition: max-height 0.5s;
            -ms-transition: max-height 0.5s;
            -o-transition: max-height 0.5s;
            transition: max-height 0.5s;
        }

        .error-container .action-panel {
            margin: 10px;
            position: absolute;
            top: 0px;
            right: 0px;
        }

        .error-container .card-panel {
            position: relative;
        }

        .error-container.expand .card-panel {
            max-height: 500px;
            position: relative;
            overflow-y: scroll;
        }
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Data Migration</h3>
            </div>
        </div>
        @if (session('errorscollection') && session('errorscollection')->count() >= 1)
            <div class="error-container col s12">
                <div class="card-panel red lighten-3">
                    <h4>Errors</h4>
                    <div class="action-panel">
                        <a class="error-expand-btn" href="javascript:;"><i class="material-icons">expand_more</i></a>
                    </div>
                    @foreach(session('errorscollection') as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col s12 m3 xl2">
                @include("partials/sidenav")
            </div>
            <div class="col s12 m9 xl10">
                <div class="card-panel">
                    <div class="row">
                        <form id="import-contact" method="post" enctype="multipart/form-data" action="{{ route('migration.import.contact') }}">
                            <div class="input-field col s12 m8">
                                <div class="file-field input-field">
                                    <div class="btn btn-link tooltipped" data-position="left" data-tooltip="Duplicate Contacts will not be imported">
                                        <span>File</span>
                                        <input id="contactimport" name="contactimport" type="file" accept="text/csv" data-maxsize="10M"/>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="contactimportfp" name="contactimportfp" class="file-path validate" type="text" data-parsley-required="false" data-parsley-fileuploaded="true" data-parsley-trigger="change" placeholder="Contact .csv File"/>
                                    </div>
                                </div>
                                <label for="contactimport" class="label-validation">
                                    Contact Import
                                </label>
                            </div>
                            <div class="input-field col s12 m4">
                                {{ csrf_field() }}
                                <button class="btn waves-effect waves-light full-width" type="submit" name="action">Import</button>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <div class="row">
                        <form id="import-invoice" method="post" enctype="multipart/form-data" action="{{ route('migration.import.invoice') }}">
                            <div class="input-field col s12 m8">
                                <div class="file-field input-field">
                                    <div class="btn btn-link tooltipped" data-position="left" data-tooltip="Duplicate Invoices will not be imported">
                                        <span>File</span>
                                        <input id="invoiceimport" name="invoiceimport" type="file" accept="text/csv" data-maxsize="10M"/>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="invoiceimportfp" name="invoiceimportfp" class="file-path validate" type="text" data-parsley-required="false" data-parsley-fileuploaded="true" data-parsley-trigger="change" placeholder="Invoice .csv File"/>
                                    </div>
                                </div>
                                <label for="invoiceimport" class="label-validation">
                                    Invoice Import
                                </label>
                            </div>
                            <div class="input-field col s12 m4">
                                {{ csrf_field() }}
                                <button class="btn waves-effect waves-light full-width" type="submit" name="action">Import</button>
                            </div>
                        </form>
                    </div>
                    <hr>
                    <div class="row">
                        <form id="import-payment" method="post" enctype="multipart/form-data" action="{{ route('migration.import.payment') }}">
                            <div class="input-field col s12 m8">
                                <div class="file-field input-field">
                                    <div class="btn btn-link tooltipped" data-position="left" data-tooltip="Duplicate Payments will not be imported">
                                        <span>File</span>
                                        <input id="paymentimport" name="paymentimport" type="file" accept="text/csv" data-maxsize="10M"/>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="paymentimportfp" name="paymentimportfp" class="file-path validate" type="text" data-parsley-required="false" data-parsley-fileuploaded="true" data-parsley-trigger="change" placeholder="Payment .csv File"/>
                                    </div>
                                </div>
                                <label for="paymentimport" class="label-validation">
                                    Payment Import
                                </label>
                            </div>
                            <div class="input-field col s12 m4">
                                {{ csrf_field() }}
                                <button class="btn waves-effect waves-light full-width" type="submit" name="action">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            $('.error-container').on('click', '.error-expand-btn', function() {
                if ($(this).children('.material-icons').html() == "expand_more")
                {
                    $(this).closest('.error-container').addClass('expand');
                    $(this).children('.material-icons').html("expand_less");
                }
                else
                {
                    $(this).closest('.error-container').removeClass('expand');
                    $(this).children('.material-icons').html("expand_more");
                }
            });
        });
    </script>
@stop