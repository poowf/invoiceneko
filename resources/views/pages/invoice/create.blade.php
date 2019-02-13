@extends("layouts.default", ['page_title' => 'Invoice | Create'])

@section("head")
    <link href="{{ asset(mix('/assets/css/selectize.css')) }}" rel="stylesheet" type="text/css">
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
                            <span class="helper-text"></span>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="netdays" name="netdays" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('netdays') }}" placeholder="Net Days">
                            <label for="netdays" class="label-validation">Net Days</label>
                            <span class="helper-text"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <select id="client_id" name="client_id" data-parsley-required="true" data-parsley-trigger="change">
                                <option disabled="" selected="selected" value="">Pick a Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @if(old('client_id') == $client->id) selected @endif>{{ $client->companyname }}</option>
                                @endforeach
                            </select>
                            <label for="client_id" class="label-validation">Client</label>
                            <span class="helper-text"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="notify" class="label-validation">Auto-Notify</label>
                            <div class="switch mtop20">
                                <label class="tooltipped" data-position="bottom" data-tooltip="Automatically send Invoice to customers on Invoice Date">
                                    No
                                    <input id="notify" name="notify" type="checkbox">
                                    <span class="lever"></span>
                                    Yes
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <label for="recurring-invoice-check" class="label-validation">Recurring Invoice</label>
                            <div class="switch mtop20">
                                <label>
                                    No
                                    <input id="recurring-invoice-check" name="recurring-invoice-check" type="checkbox">
                                    <span class="lever"></span>
                                    Yes
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="recurring-invoice-container" class="row">
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
                    @if(old('item_name'))
                        @foreach(old('item_name') as $key => $item)
                        <div id="invoice_item_{{ $key }}" class="card-panel">
                            <div class="row">
                                <div class="input-field col s12 l8">
                                    <input id="item_name_{{ $key }}" name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_name.' . $key) }}" placeholder="Item Name">
                                    <label for="item_name_{{ $key }}" class="label-validation">Name</label>
                                    <span class="helper-text"></span>
                                </div>
                                <div class="input-field col s6 l2">
                                    <input id="item_quantity_{{ $key }}" name="item_quantity[]" class="item-quantity-input" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_quantity.' . $key) }}" data-parsley-min="1" placeholder="Item Quantity">
                                    <label for="item_quantity_{{ $key }}" class="label-validation">Quantity</label>
                                    <span class="helper-text"></span>
                                </div>
                                <div class="input-field col s6 l2">
                                    <input id="item_price_{{ $key }}" name="item_price[]" class="item-price-input" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_price.' . $key) }}" placeholder="Item Price">
                                    <label for="item_price_{{ $key }}" class="label-validation">Price</label>
                                    <span class="helper-text"></span>
                                </div>
                                <div class="input-field col s12 mtop30">
                                    <textarea id="item_description_{{ $key }}" name="item_description[]" class="item-description-textarea trumbowyg-textarea" data-parsley-required="false" data-parsley-trigger="change" placeholder="Item Description">{{ old('item_description.' . $key) }}</textarea>
                                    <label for="item_description_{{ $key }}" class="label-validation">Description</label>
                                    <span class="helper-text"></span>
                                </div>
                            </div>
                            @if($key != 0)
                                <div class="row">
                                    <button data-id="false" data-count="{{ $key }}" class="invoice-item-delete-btn btn waves-effect waves-light col s12 m3 offset-m9 red">Delete</button>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div id="invoice_item_0" class="card-panel">
                            <div class="row">
                                <div class="input-field col s12 l8">
                                    <select id="item_name_0" name="item_name[]" class="item-list-selector" data-parsley-required="true" data-parsley-trigger="change">
                                        <option disabled="" selected="selected" value="">Pick an Item or Create a new one</option>
                                    </select>
                                    <label for="item_name_0" class="label-validation">Name</label>
                                    <span class="helper-text"></span>
                                </div>
                                <div class="input-field col s6 l2">
                                    <input id="item_quantity_0" name="item_quantity[]" class="item-quantity-input" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_quantity.0') }}" data-parsley-min="1" placeholder="Item Quantity">
                                    <label for="item_quantity_0" class="label-validation">Quantity</label>
                                    <span class="helper-text"></span>
                                </div>
                                <div class="input-field col s6 l2">
                                    <input id="item_price_0" name="item_price[]" class="item-price-input" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_price.0') }}" placeholder="Item Price">
                                    <label for="item_price_0" class="label-validation">Price</label>
                                    <span class="helper-text"></span>
                                </div>
                                <div class="input-field col s12 mtop30">
                                    <textarea id="item_description_0" name="item_description[]" class="item-description-textarea trumbowyg-textarea" data-parsley-required="false" data-parsley-trigger="change" placeholder="Item Description">{{ old('item_description.0') }}</textarea>
                                    <label for="item_description_0" class="label-validation">Description</label>
                                    <span class="helper-text"></span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        {{ csrf_field() }}
                        <button class="btn btn-link waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Create</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Delete Invoice Item?</p>
        </div>
        <div class="modal-footer">
            <a href="javascript:;" class=" modal-action waves-effect black-text waves-green btn-flat btn-deletemodal invoice-item-confirm-delete-btn">Delete</a>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            let invoiceitemcount = {{ old('item_name') ? (count (old('item_name')) - 1) : 0 }};
            let itemoptions = [ @foreach($itemtemplates as $itemtemplate){ id:'{{ $itemtemplate->id }}', name:'{{ $itemtemplate->name }}' },@endforeach ];

            Unicorn.initParsleyValidation('#create-invoice');
            Unicorn.initDatepicker('#date', '1950', new Date("{{ Carbon\Carbon::now()->addYear()->toDateTimeString() }}").getFullYear(), new Date("{{ Carbon\Carbon::now()->toDateTimeString() }}"));
            Unicorn.initSelectize('#client_id');
            Unicorn.initItemElement(itemoptions);
            Unicorn.initItemConfirmationTrigger('#invoice-items-container', '.invoice-item-delete-btn', '#delete-confirmation', '.invoice-item-confirm-delete-btn', 'click');
            Unicorn.executeItemDeleteTrigger('#delete-confirmation', '.invoice-item-confirm-delete-btn', 'invoice', 'click');
            Unicorn.initListener('#create-invoice', '#invoice-item-add', 'click', function (event) {
                Unicorn.initNewItem(++invoiceitemcount, 'invoice-items-container', 'invoice', itemoptions);
            });
            Unicorn.initListener('#invoice-items-container', '.item-list-selector', 'change', function (event, element) {
                Unicorn.retrieveItemTemplate("/{{ \App\Library\Poowf\Unicorn::getCompanyKey() }}", element.siblings().find('.selected').attr('data-id'), element, Unicorn.setItemTemplate);
            });

            Unicorn.initListener('#create-invoice', '#recurring-invoice-check', 'change', function (event, element) {
                if (element.prop('checked')) {
                    initRecurringElements('recurring-invoice-container');
                } else {
                    $('#recurring-invoice-container').html('');
                }
            });

            function initRecurringElements(elementId)
            {
                let recurringelements = '<div class="input-field col s4 l2"> <input id="recurring-time-interval" name="recurring-time-interval" type="number" data-parsley-min="1" data-parsley-max="730" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('recurring-time-interval') or '1' }}"> <label for="recurring-time-interval" class="label-validation">Repeats every</label> <span class="helper-text"></span></div><div class="input-field col s8 l10"> <select id="recurring-time-period" name="recurring-time-period" data-parsley-required="true" data-parsley-trigger="change"><option value="day">Day</option><option value="week">Week</option><option value="month" selected>Month</option><option value="year">Year</option> </select> <label class="label-validation recurring-time-period">Period</label> <span class="helper-text"></span></div><div class="radio-field col s12"> <label id="rbtn-label" class="rbtn-label" for="recurring-until">Until</label><p> <label> <input id="recurring-until-never" name="recurring-until" type="radio" value="never" data-parsley-required="true" data-parsley-trigger="change" checked> <span>The End of Time</span> </label></p><p> <label> <input id="recurring-until-occurence" name="recurring-until" type="radio" value="occurence"> <span> After <input id="recurring-until-occurence-number" name="recurring-until-occurence-number" class="radio-input-inline radio-input-digit" type="number" data-parsley-required="false" data-parsley-min="1" data-parsley-max="730" data-parsley-trigger="change" value="{{ old('recurring-until-occurence-number') ?? '1' }}"> Occurences </span> </label></p><p> <label> <input id="recurring-until-date" name="recurring-until" type="radio" value="date"> <span> On <input id="recurring-until-date-value" name="recurring-until-date-value" class="datepicker radio-input-inline radio-input-date" type="text" data-parsley-required="false" data-parsley-trigger="change" value="{{ old('recurring-until-date-value') ?? \Carbon\Carbon::now()->addMonth(1)->format('j F, Y') }}"> </span> </label></p> <span class="helper-text manual-validation"></span></div>';
                $('#' + elementId).append(recurringelements);
                Unicorn.initSelectize('#recurring-time-period');
                Unicorn.initDatepicker('#recurring-until-date-value', new Date("{{ Carbon\Carbon::now()->toDateTimeString() }}"), new Date("{{ Carbon\Carbon::now()->addYears(50)->toDateTimeString() }}").getFullYear(), new Date("{{ Carbon\Carbon::now()->addMonth()->toDateTimeString() }}"));
            }
        });
    </script>
@stop