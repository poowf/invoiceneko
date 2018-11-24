@extends("layouts.default", ['page_title' => 'Payment | Edit'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Edit Payment</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="edit-payment" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="amount" name="amount" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change"  value="{{ $payment->amount ?? '' }}" placeholder="Payment Amount {{ $payment->amount ?? '' }}">
                                <label for="amount" class="label-validation">Amount</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="receiveddate" name="receiveddate" class="datepicker" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $payment->receiveddate ?? Carbon\Carbon::now()->toDateTimeString() }}" placeholder="Payment Date">
                                <label for="receiveddate" class="label-validation">Received Date</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="mode" name="mode" data-parsley-required="false" data-parsley-trigger="change" placeholder="Select an Option">
                                    <option value="" selected="selected"></option>
                                    <option value="Cheque" @if($payment->mode == 'Cheque') selected @endif>Cheque</option>
                                    <option value="Bank Transfer" @if($payment->mode == 'Bank Transfer') selected @endif>Bank Transfer</option>
                                </select>
                                <label for="mode" class="label-validation">Payment Mode</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="notes" name="notes" class="materialize-textarea" data-parsley-trigger="change" placeholder="Notes">{{ $payment->notes ?? '' }}</textarea>
                                <label for="notes" class="label-validation">Notes</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
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
                sortField: 'text',
                onChange: function(value, isOnInitialize) {
                    this.$input.parsley().validate();
                }
            });

            $('.datepicker').datepicker({
                autoClose: 'false',
                format: 'd mmmm, yyyy',
                yearRange: [1950, 2018],
                defaultDate: new Date("{{ $payment->receiveddate }}"),
                setDefaultDate: true,
                onSelect: function() {
                    // var date = $(this)[0].formats.yyyy() + '-' + $(this)[0].formats.mm() + '-' + $(this)[0].formats.dd()
                    // $('#receiveddate').val(date);
                }
            });

            $('#amount').on('change', function(){
                $(this).val(parseFloat($(this).val()).toFixed(2));
            });

            Unicorn.initParsleyValidation('#edit-payment');
        });
    </script>
@stop