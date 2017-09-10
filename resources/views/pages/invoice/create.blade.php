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
                <h3>Create Invoice</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="signup" method="post">
                <div class="card-panel">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="invoiceid" name="invoiceid" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ old('invoiceid') }}">
                            <label for="invoiceid" class="label-validation">Invoice ID</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="date" name="date" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('date') }}">
                            <label for="date" class="label-validation">Date</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="duedate" name="duedate" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('duedate') }}">
                            <label for="duedate" class="label-validation">Due Date</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="netdays" name="netdays" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('netdays') }}">
                            <label for="netdays" class="label-validation">Net Days</label>
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
                                <input id="item_name_0" name="item_name_0" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_name_0') }}">
                                <label for="item_name_0" class="label-validation">Name</label>
                            </div>
                            <div class="input-field col s2">
                                <input id="item_quantity_0" name="item_quantity_0" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_quantity_0') }}">
                                <label for="item_quantity_0" class="label-validation">Quantity</label>
                            </div>
                            <div class="input-field col s2">
                                <input id="item_price_0" name="item_price_0" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_price_0') }}">
                                <label for="item_price_0" class="label-validation">Price</label>
                            </div>
                            <div class="input-field col s12">
                                <textarea id="item_description_0" name="item_description_0" class="materialize-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description">{{ old('item_description_0') }}</textarea>
                                <label for="item_description_0" class="label-validation">Description</label>
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

            $('#invoice-item-add').on('click', function() {
                initInvoiceItem(++invoiceitemcount, 'invoice-items-container');
            });

            function initInvoiceItem(count, elementid) {
                var invoiceitem = '<div id="invoice_item_' + count + '" class="card-panel"> <div class="row"> <div class="input-field col s8"> <input id="item_name_' + count + '" name="item_name_' + count + '" type="text" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_name_' + count + '" class="label-validation">Name</label> </div> <div class="input-field col s2"> <input id="item_quantity_' + count + '" name="item_quantity_' + count + '" type="number" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_quantity_' + count + '" class="label-validation">Quantity</label> </div> <div class="input-field col s2"> <input id="item_price_' + count + '" name="item_price_' + count + '" type="number" data-parsley-required="true" data-parsley-trigger="change"> <label for="item_price_' + count + '" class="label-validation">Price</label> </div> <div class="input-field col s12"> <textarea id="item_description_' + count + '" name="item_description_' + count + '" class="materialize-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description"></textarea> <label for="item_description_' + count + '" class="label-validation">Description</label> </div> </div> </div>';
                $('#' + elementid).append(invoiceitem);
            }
        });
    </script>
@stop