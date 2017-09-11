@extends("layouts/default")

@section("head")
    <title>Invoice Plz</title>
    <style>
        .selectize-control {
            margin-top: 12px;
        }
        .selectize-dropdown, .selectize-input, .selectize-input input {
            font-size: 1rem;
        }

        .selectize-control.single .selectize-input {
            border: none;
            box-shadow: none;
            border-bottom: 1px solid #9e9e9e;
            border-radius: 0;
        }
        .selectize-control.single .selectize-input input {
            height: auto;
        }
        .selectize-dropdown {
            border: none;
            border-radius: 0;
            box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 1px 5px 0 rgba(0,0,0,0.12), 0 3px 1px -2px rgba(0,0,0,0.2);
        }

        .selectize-dropdown [data-selectable], .selectize-dropdown .optgroup-header {
            min-height: 50px;
            line-height: 50px;
            padding: 0 8px;
        }
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
                <form id="signup" method="post" enctype="multipart/form-data">
                <div class="card-panel">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="invoiceid" name="invoiceid" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $company->slug . '-' . $invoicenumber }}">
                            <label for="invoiceid" class="label-validation">Invoice ID</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="date" name="date" class="datepicker" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('date') }}">
                            <label for="date" class="label-validation">Date</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="netdays" name="netdays" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('netdays') }}">
                            <label for="netdays" class="label-validation">Net Days</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <select id="client_id" name="client_id" data-parsley-required="true" data-parsley-trigger="change">
                                <option disabled="" selected="selected" value="">Pick a Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->companyname }}</option>
                                @endforeach
                            </select>
                            <label for="client_id" class="label-validation">Client</label>
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
                                <input id="item_name" name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_name') }}">
                                <label for="item_name" class="label-validation">Name</label>
                            </div>
                            <div class="input-field col s2">
                                <input id="item_quantity" name="item_quantity[]" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_quantity') }}">
                                <label for="item_quantity" class="label-validation">Quantity</label>
                            </div>
                            <div class="input-field col s2">
                                <input id="item_price" name="item_price[]" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_price') }}">
                                <label for="item_price" class="label-validation">Price</label>
                            </div>
                            <div class="input-field col s12">
                                <textarea id="item_description" name="item_description[]" class="materialize-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description">{{ old('item_description') }}</textarea>
                                <label for="item_description" class="label-validation">Description</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        {{ csrf_field() }}
                        <button class="btn waves-effect waves-light col s2 offset-s10" type="submit" name="action">Create</button>
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
                var invoiceitem = '<div id="invoice_item_' + count + '" class="card-panel"> <div class="row"> <div class="input-field col s8"> <input id="item_name" name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_name" class="label-validation">Name</label> </div> <div class="input-field col s2"> <input id="item_quantity" name="item_quantity[]" type="number" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_quantity" class="label-validation">Quantity</label> </div> <div class="input-field col s2"> <input id="item_price" name="item_price[]" type="number" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_price" class="label-validation">Price</label> </div> <div class="input-field col s12"> <textarea id="item_description" name="item_description[]" class="materialize-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description"></textarea> <label for="item_description" class="label-validation">Description</label> </div> </div> </div>';
                $('#' + elementid).append(invoiceitem);
            }
        });
    </script>
@stop