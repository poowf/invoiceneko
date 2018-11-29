@extends("layouts.default", ['page_title' => 'Invoice | Create'])

@section("head")
    <link href="{{ mix('/assets/css/selectize.css') }}" rel="stylesheet" type="text/css">
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
                                <input id="item_quantity_0" name="item_quantity[]" class="item-quantity-input" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_quantity') }}" data-parsley-min="1" placeholder="Item Quantity">
                                <label for="item_quantity_0" class="label-validation">Quantity</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6 l2">
                                <input id="item_price_0" name="item_price[]" class="item-price-input" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_price') }}" placeholder="Item Price">
                                <label for="item_price_0" class="label-validation">Price</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 mtop30">
                                <textarea id="item_description_0" name="item_description[]" class="item-description-textarea trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description">{{ old('item_description') }}</textarea>
                                <label for="item_description_0" class="label-validation">Description</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
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
            let invoiceitemcount = 0;

            Unicorn.initParsleyValidation('#create-invoice');

            $('.trumbowyg-textarea').trumbowyg({
                svgPath: '/assets/fonts/trumbowygicons.svg',
                removeformatPasted: true,
                resetCss: true,
                autogrow: true,
            });

            $('#date').datepicker({
                autoClose: 'false',
                format: 'd mmmm, yyyy',
                yearRange: [1950, {{ \Carbon\Carbon::now()->addYear()->format('Y') }}],
                defaultDate: new Date("{{ Carbon\Carbon::now()->toDateTimeString()  }}"),
                setDefaultDate: true,
                onSelect: function() {
                    // let date = $(this)[0].formats.yyyy() + '-' + $(this)[0].formats.mm() + '-' + $(this)[0].formats.dd()
                    // $('#receiveddate').val(date);
                }
            });

            Unicorn.initSelectize('#client_id');
            initElements();

            $('#invoice-item-add').on('click', function() {
                initInvoiceItem(++invoiceitemcount, 'invoice-items-container');
            });

            $('#create-invoice').on('change', '#recurring-invoice-check', function (event) {
                if ($(this).prop('checked')) {
                    initRecurringElements('recurring-invoice-container');
                } else {
                    $('#recurring-invoice-container').html('');
                }
            });

            function initElements()
            {
                $('.trumbowyg-textarea').trumbowyg({
                    svgPath: '/assets/fonts/trumbowygicons.svg',
                    removeformatPasted: true,
                    resetCss: true,
                    autogrow: true,
                });

                //Explicit selection otherwise will change the select into a multiple select if only selecting by css class
                $('select.item-list-selector').selectize({
                    create: true,
                    dataAttr: 'data-id',
                    valueField: 'name',
                    labelField: 'name',
                    searchField: ['name'],
                    onChange: function(value, isOnInitialize) {
                        this.$input.parsley().validate();
                    },
                    options: [
                            @foreach($itemtemplates as $itemtemplate){ id:'{{ $itemtemplate->id }}', name:'{{ $itemtemplate->name }}' },@endforeach
                    ],
                    render: {
                        option: function(data) {
                            return '<div class="option" data-selectable="" data-id="' + data.id +'" data-value="' + data.name +'">' + data.name +'</div>';
                        }
                    }
                });
            }

            function initRecurringElements(elementid)
            {
                let recurringelements = '<div class="input-field col s4 l2"> <input id="recurring-time-interval" name="recurring-time-interval" type="number" data-parsley-min="1" data-parsley-max="730" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('recurring-time-interval') or '1' }}"> <label for="recurring-time-interval" class="label-validation">Repeats every</label> <span class="helper-text"></span></div><div class="input-field col s8 l10"> <select id="recurring-time-period" name="recurring-time-period" data-parsley-required="true" data-parsley-trigger="change"><option value="day">Day</option><option value="week">Week</option><option value="month" selected>Month</option><option value="year">Year</option> </select> <label class="recurring-time-period"></label> <span class="helper-text"></span></div><div class="radio-field col s12"> <label id="rbtn-label" class="rbtn-label" for="recurring-until">Until</label><p> <label> <input id="recurring-until-never" name="recurring-until" type="radio" value="never" data-parsley-required="true" data-parsley-trigger="change" checked> <span>The End of Time</span> </label></p><p> <label> <input id="recurring-until-occurence" name="recurring-until" type="radio" value="occurence"> <span> After <input id="recurring-until-occurence-number" name="recurring-until-occurence-number" class="radio-input-inline radio-input-digit" type="number" data-parsley-required="false" data-parsley-min="1" data-parsley-max="730" data-parsley-trigger="change" value="{{ old('recurring-until-occurence-number') ?? '1' }}"> Occurences </span> </label></p><p> <label> <input id="recurring-until-date" name="recurring-until" type="radio" value="date"> <span> On <input id="recurring-until-date-value" name="recurring-until-date-value" class="datepicker radio-input-inline radio-input-date" type="text" data-parsley-required="false" data-parsley-trigger="change" value="{{ old('recurring-until-date-value') ?? \Carbon\Carbon::now()->addMonth(1)->format('j F, Y') }}"> </span> </label></p> <span class="helper-text manual-validation"></span></div>';
                $('#' + elementid).append(recurringelements);
                Unicorn.initSelectize('#recurring-time-period');

                $('#recurring-until-date-value').datepicker({
                    autoClose: 'false',
                    format: 'd mmmm, yyyy',
                    yearRange: [{{ \Carbon\Carbon::now()->format('Y') }}, {{ \Carbon\Carbon::now()->addYears(50)->format('Y') }}],
                    onSelect: function() {
                        // let date = $(this)[0].formats.yyyy() + '-' + $(this)[0].formats.mm() + '-' + $(this)[0].formats.dd()
                        // $('#receiveddate').val(date);
                    }
                });
            }

            function initInvoiceItem(count, elementid) {
                let invoiceitem = '<div id="invoice_item_' + count + '" class="card-panel"><div class="row"><div class="input-field col s12 l8"> <select id="item_name_' + count + '" name="item_name[]" class="item-list-selector" data-parsley-required="true" data-parsley-trigger="change"><option disabled="" selected="selected" value="">Pick an Item or Create a new one</option> </select> <label for="item_name_' + count + '" class="label-validation">Name</label> <span class="helper-text"></span></div><div class="input-field col s6 l2"> <input id="item_quantity_' + count + '" name="item_quantity[]" class="item-quantity-input" type="number" data-parsley-required="true" data-parsley-trigger="change" data-parsley-min="1" placeholder="Item Quantity"> <label for="item_quantity_' + count + '" class="label-validation">Quantity</label> <span class="helper-text"></span></div><div class="input-field col s6 l2"> <input id="item_price_' + count + '" name="item_price[]" class="item-price-input" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Price"> <label for="item_price_' + count + '" class="label-validation">Price</label> <span class="helper-text"></span></div><div class="input-field col s12 mtop30"><textarea id="item_description_' + count + '" name="item_description[]" class="item-description-textarea trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description"></textarea><label for="item_description_' + count + '" class="label-validation">Description</label> <span class="helper-text"></span></div></div><div class="row"> <button data-id="false" data-count="' + count + '" class="invoice-item-delete-btn btn waves-effect waves-light col s12 m3 offset-m9 red">Delete</button></div></div>';
                $('#' + elementid).append(invoiceitem);
                initElements();
                $('html, body').animate({
                    scrollTop: $("#invoice_item_" + count).offset().top
                }, 500, 'linear');
            }

            function retrieveItemTemplate(itemtemplate_id, element, callback) {
                if (typeof itemtemplate_id !== typeof undefined && itemtemplate_id !== false) {
                    $.ajax({
                        type: "GET",
                        url: "/{{ app('request')->route('company')->domain_name }}/itemtemplate/" + itemtemplate_id +"/retrieve",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                        .done(function(data) {
                            callback(element, data);
                        })
                        .fail(function(jqXHR, textStatus) {
                            console.log(jqXHR);
                            console.log(textStatus);
                        })
                        .always(function() {
                            console.log("finish");
                        });
                }
            }

            function setItemTemplate(element, itemtemplate) {
                element.val(itemtemplate.name);
                let quantityElement = element.parentsUntil(".card-panel").find('.item-quantity-input');
                let priceElement = element.parentsUntil(".card-panel").find('.item-price-input');
                let descriptionElement = element.parentsUntil(".card-panel").find('.item-description-textarea');

                quantityElement.val(itemtemplate.quantity);
                priceElement.val(itemtemplate.price);
                descriptionElement.trumbowyg('html', itemtemplate.description);
            }

            $('#invoice-items-container').on('click', '.invoice-item-delete-btn', function (event) {
                event.preventDefault();
                $('#delete-confirmation').modal('open');

                let itemid = $(this).attr('data-id');
                let count = $(this).attr('data-count');

                $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-id', itemid);
                $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-count', count);
            });

            $('#invoice-items-container').on('change', '.item-list-selector', function (event) {
                retrieveItemTemplate($(this).siblings().find('.selected').attr('data-id'), $(this), setItemTemplate);
            });

            $('#delete-confirmation').on('click', '.invoice-item-confirm-delete-btn', function (event) {
                event.preventDefault();

                let itemid = $(this).attr('data-id');
                let count = $(this).attr('data-count');

                if (itemid == "false") {
                    $('#invoice_item_' + count).remove();
                    $('#delete-confirmation').modal('close');
                }
            });
        });
    </script>
@stop