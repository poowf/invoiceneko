@extends("layouts.default", ['page_title' => 'Payment | Standalone Create'])

@section("head")
    <link href="{{ mix('/assets/css/selectize.css') }}" rel="stylesheet" type="text/css">
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
                                <select id="invoice_id" name="invoice_id" data-parsley-required="true" data-parsley-trigger="change">
                                    <option disabled="" selected="selected" value="">Pick an Invoice</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}">{{ $invoice->nice_invoice_id }}</option>
                                    @endforeach
                                </select>
                                <label for="invoice_id" class="label-validation">Invoice ID</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="amount" name="amount" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change"  value="{{ old('amount') }}" placeholder="Payment Amount">
                                <label for="amount" class="label-validation">Amount</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="receiveddate" name="receiveddate" class="datepicker" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ old('receiveddate') }}" placeholder="Payment Date">
                                <label for="receiveddate" class="label-validation">Received Date</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="mode" name="mode" data-parsley-required="false" data-parsley-trigger="change" value="{{ old('mode') }}" placeholder="Select an Option">
                                    <option value="" selected="selected"></option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                                <label for="mode" class="label-validation">Payment Mode</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="notes" name="notes" class="materialize-textarea" data-parsley-required="false" data-parsley-trigger="change" placeholder="Notes">{{ old('notes')  }}</textarea>
                                <label for="notes" class="label-validation">Notes</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Submit</button>
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
            $('#mode').selectize({
                create: true,
                sortField: 'text'
            });

            $('.datepicker').datepicker({
                autoClose: 'false',
                format: 'd mmmm, yyyy',
                yearRange: [1950, 2018],
                onSelect: function() {
                    // var date = $(this)[0].formats.yyyy() + '-' + $(this)[0].formats.mm() + '-' + $(this)[0].formats.dd()
                    // $('#receiveddate').val(date);
                }
            });

            $('#amount').on('change', function(){
                $(this).val(parseFloat($(this).val()).toFixed(2));
            });

            $('#invoice_id').selectize();

            Unicorn.initParsleyValidation('#create-payment');
        });
    </script>
@stop