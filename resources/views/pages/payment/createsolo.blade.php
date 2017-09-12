@extends("layouts/default")

@section("head")
    <title>Invoice Plz</title>
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Log Payment</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="create-payment" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="invoice_id" name="invoice_id" class="test" data-parsley-required="true" data-parsley-trigger="change">
                                    <option disabled="" selected="selected" value="">Pick an Invoice</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}">{{ $invoice->invoiceid }}</option>
                                    @endforeach
                                </select>
                                <label for="invoice_id" class="label-validation">Invoice ID</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="amount" name="amount" type="number" data-parsley-required="true" data-parsley-trigger="change"  value="{{ old('amount') }}" placeholder="Payment Amount">
                                <label for="amount" class="label-validation">Amount</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="receiveddate" name="receiveddate" class="datepicker" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('receiveddate') }}" placeholder="Payment Date">
                                <label for="receiveddate" class="label-validation">Received Date</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="mode" name="mode" type="text" data-parsley-required="false" data-parsley-trigger="change" value="{{ old('mode') }}" placeholder="Payment Mode">
                                <label for="mode" class="label-validation">Payment Mode</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="notes" name="notes" class="materialize-textarea" data-parsley-required="false" data-parsley-trigger="change" placeholder="Notes">{{ old('notes')  }}</textarea>
                                <label for="notes" class="label-validation">Notes</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m2 offset-m10" type="submit" name="action">Submit</button>
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
            $('.datepicker').pickadate({
                formatSubmit: 'yyyy-mm-dd',
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year,
                today: 'Today',
                clear: 'Clear',
                close: 'Ok',
                closeOnSelect: true // Close upon selecting a date,
            });

            $('#invoice_id').selectize();

            $('#create-payment').parsley({
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
                    if (velem.$element.is('select')) {
                        velem.$element.siblings('.selectize-control').removeClass('invalid').addClass('valid');
                        //velem.$element.parent('.select-wrapper').removeClass('invalid').addClass('valid');
                        //velem.$element.siblings('.select-dropdown').removeClass('invalid').addClass('valid');
                    }
                })
                .on('field:error', function(velem) {
                    if (velem.$element.is('select')) {
                        velem.$element.siblings('.selectize-control').removeClass('valid').addClass('invalid');

                        //velem.$element.parent('.select-wrapper').removeClass('valid').addClass('invalid');
                        //velem.$element.siblings('.select-dropdown').removeClass('valid').addClass('invalid');
                        //velem.$element.parent('.select-wrapper').siblings('label').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    }
                })
                .on('form:submit', function(velem) {
                });
        });
    </script>
@stop