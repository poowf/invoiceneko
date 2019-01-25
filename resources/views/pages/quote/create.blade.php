@extends("layouts.default", ['page_title' => 'Quote | Create'])

@section("head")
    <link href="{{ asset(mix('/assets/css/selectize.css')) }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Create Quote</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="create-quote" method="post" enctype="multipart/form-data">
                <div class="card-panel">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="nice_quote_id" name="nice_quote_id" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $quotenumber ?? '' }}">
                            <label for="nice_quote_id" class="label-validation">Quote ID</label>
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
                            <select id="client_id" name="client_id" class="test" data-parsley-required="true" data-parsley-trigger="change">
                                <option disabled="" selected="selected" value="">Pick a Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @if(old('client_id') == $client->id) selected @endif>{{ $client->companyname }}</option>
                                @endforeach
                            </select>
                            <label for="client_id" class="label-validation">Client</label>
                            <span class="helper-text"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s6">
                        <h3>Items</h3>
                    </div>
                    <div class="col s6">
                        <a id="quote-item-add" class="btn-floating btn-large waves-effect waves-light red right"><i class="material-icons">add</i></a>
                    </div>
                </div>
                <div id="quote-items-container">
                    <div id="quote_item_0" class="card-panel">
                        <div class="row">
                            <div class="input-field col s12 l8">
                                <select id="item_name_0" name="item_name[]" class="item-list-selector" data-parsley-required="true" data-parsley-trigger="change">
                                    <option disabled="" selected="selected" value="">Pick an Item or Create a new one</option>
                                </select>
                                <label for="item_name_0" class="label-validation">Name</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6 l2">
                                <input id="item_quantity_0" name="item_quantity[]" class="item-quantity-input" type="number" data-parsley-required="true" data-parsley-trigger="change" data-parsley-min="1" value="{{ old('item_quantity') }}" placeholder="Item Quantity">
                                <label for="item_quantity_0" class="label-validation">Quantity</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6 l2">
                                <input id="item_price_0" name="item_price[]" class="item-price-input" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('item_price') }}" placeholder="Item Price">
                                <label for="item_price_0" class="label-validation">Price</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 mtop30">
                                <textarea id="item_description_0" name="item_description[]" class="item-description-textarea trumbowyg-textarea" data-parsley-required="false" data-parsley-trigger="change" placeholder="Item Description">{{ old('item_description') }}</textarea>
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
            <p>Delete Quote Item?</p>
        </div>
        <div class="modal-footer">
            <a href="javascript:;" class=" modal-action waves-effect black-text waves-green btn-flat btn-deletemodal quote-item-confirm-delete-btn">Delete</a>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            let quoteitemcount = 0;
            let itemoptions = [ @foreach($itemtemplates as $itemtemplate){ id:'{{ $itemtemplate->id }}', name:'{{ $itemtemplate->name }}' },@endforeach ];

            Unicorn.initParsleyValidation('#create-quote');
            Unicorn.initDatepicker('#date', '1950', new Date("{{ Carbon\Carbon::now()->addYear()->toDateTimeString() }}").getFullYear(), new Date("{{ Carbon\Carbon::now()->toDateTimeString() }}"));
            Unicorn.initSelectize('#client_id');
            Unicorn.initItemElement(itemoptions);
            Unicorn.initItemConfirmationTrigger('#quote-items-container', '.quote-item-delete-btn', '#delete-confirmation', '.quote-item-confirm-delete-btn', 'click');
            Unicorn.executeItemDeleteTrigger('#delete-confirmation', '.quote-item-confirm-delete-btn', 'quote', 'click');
            Unicorn.initListener('#create-quote', '#quote-item-add', 'click', function (event) {
                Unicorn.initNewItem(++quoteitemcount, 'quote-items-container', 'quote', itemoptions);
            });
            Unicorn.initListener('#quote-items-container', '.item-list-selector', 'change', function (event, element) {
                Unicorn.retrieveItemTemplate("/{{ app('request')->route('company')->domain_name }}", element.siblings().find('.selected').attr('data-id'), element, Unicorn.setItemTemplate);
            });
        });
    </script>
@stop