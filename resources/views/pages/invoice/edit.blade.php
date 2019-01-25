@extends("layouts.default", ['page_title' => 'Invoice | Edit'])

@section("head")
    <link href="{{ asset(mix('/assets/css/selectize.css')) }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Edit Invoice</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="edit-invoice" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="nice_invoice_id" name="nice_invoice_id" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $invoice->nice_invoice_id ?? '' }}" disabled>
                                <label for="nice_invoice_id" class="label-validation">Invoice ID</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="date" name="date" class="datepicker" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $invoice->date->format('j F, Y') ?? Carbon\Carbon::now()->format('j F, Y')  }}" placeholder="Date">
                                <label for="date" class="label-validation">Date</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="netdays" name="netdays" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ $invoice->netdays ?? '' }}" placeholder="Net Days">
                                <label for="netdays" class="label-validation">Net Days</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="client_id" name="client_id" data-parsley-required="true" data-parsley-trigger="change" disabled>
                                    <option disabled="" selected="selected" value="">Pick a Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if($invoice->client_id == $client->id) selected @endif>{{ $client->companyname ?? '' }}</option>
                                    @endforeach
                                </select>
                                <label for="client_id" class="label-validation">Client</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <label for="notify" class="label-validation">Auto-Notify</label>
                                <div class="switch mtop20">
                                    <label class="tooltipped" data-position="bottom" data-tooltip="Automatically send Invoice to customers on Invoice Date">
                                        No
                                        <input id="notify" name="notify" type="checkbox" @if($invoice->notify) checked @endif>
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
                                        <input id="recurring-invoice-check" name="recurring-invoice-check" type="checkbox" @if($invoice->recurrence) checked @endif>
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
                        @foreach($invoice->items as $key => $item)
                            <div id="invoice_item_{{ $key }}" class="card-panel">
                                <div class="row">
                                    <div class="input-field col s12 l8">
                                        <input name="item_id[]" type="hidden" data-parsley-required="true" data-parsley-trigger="change" value="{{ $item->id ?? '' }}">
                                        <input name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $item->name ?? '' }}" placeholder="Item Name">
                                        <label for="item_name" class="label-validation">Name</label>
                                        <span class="helper-text"></span>
                                    </div>
                                    <div class="input-field col s6 l2">
                                        <input name="item_quantity[]" type="number" data-parsley-required="true" data-parsley-trigger="change" data-parsley-min="1" value="{{ $item->quantity ?? '' }}" placeholder="Item Quantity">
                                        <label for="item_quantity" class="label-validation">Quantity</label>
                                        <span class="helper-text"></span>
                                    </div>
                                    <div class="input-field col s6 l2">
                                        <input name="item_price[]" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" value="{{ $item->price ?? '' }}" placeholder="Item Price">
                                        <label for="item_price" class="label-validation">Price</label>
                                        <span class="helper-text"></span>
                                    </div>
                                    <div class="input-field col s12 mtop30">
                                        <textarea id="item_description" name="item_description[]" class="trumbowyg-textarea" data-parsley-required="false" data-parsley-trigger="change" placeholder="Item Description">{{ $item->description ?? '' }}</textarea>
                                        <label for="item_description" class="label-validation">Description</label>
                                        <span class="helper-text"></span>
                                    </div>
                                </div>
                                @if($key != 0)
                                <div class="row">
                                    <button data-id="{{ $item->id }}"  data-count="{{ $key }}" class="invoice-item-delete-btn btn waves-effect waves-light col s12 m3 offset-m9 red">Delete</button>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <input id="notify-details" name="notify-details" type="hidden" value="{{ ($invoice->notify) ? 'on' : 'off' }}" data-parsley-required="true" data-parsley-trigger="change">
                            <input id="recurring-details" name="recurring-details" type="hidden" value="none" data-parsley-required="true" data-parsley-trigger="change">
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Update</button>
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

    <div id="recurring-confirmation" class="modal mini-modal">
        <div class="modal-content">
            <p>Update Recurring Invoice Details</p>
            <div class="radio-field col s12">
                <p>
                    <label>
                        <input id="recurring-details-standalone" name="recurring-details-selector" type="radio" value="standalone" data-parsley-required="false" data-parsley-trigger="change" checked>
                        <span>This invoice only</span>
                    </label>
                </p>

                <p>
                    <label>
                        <input id="recurring-details-future" name="recurring-details-selector" type="radio" value="future">
                        <span>This and all future invoices</span>
                    </label>
                </p>
                <span class="helper-text manual-validation"></span>
            </div>
        </div>
        <div class="modal-footer">
            <a href="javascript:;" class=" modal-action waves-effect black-text waves-green btn-flat recurring-invoice-update-btn">Update</a>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            @if($invoice->recurrence) initRecurringElements('recurring-invoice-container'); @endif
            let invoiceitemcount = {{ ($invoice->items()->count() - 1) ?? 0 }};
            let form = document.getElementById('edit-invoice');
            let allowFormSubmission = @if(is_null($recurrence)){{ 'true' }}@else{{ 'false' }}@endif;
            let itemoptions = [ @foreach($itemtemplates as $itemtemplate){ id:'{{ $itemtemplate->id }}', name:'{{ $itemtemplate->name }}' },@endforeach ];

            @if(!is_null($recurrence))initChangeDetection(form);@endif

            Unicorn.initParsleyValidation('#edit-invoice', function() {
                if (!allowFormSubmission) {
                    event.preventDefault();
                }

                if(hasFormChanged(form) && !allowFormSubmission)
                {
                    $('#recurring-confirmation').modal('open');
                }
                else
                {
                    allowFormSubmission = true;
                    form.submit();
                }
            });
            Unicorn.initDatepicker('#date', '1950', new Date("{{ Carbon\Carbon::now()->addYear()->toDateTimeString() }}").getFullYear(), new Date("{{ Carbon\Carbon::now()->toDateTimeString() }}"));
            Unicorn.initSelectize('#client_id');
            Unicorn.initItemElement(itemoptions);
            Unicorn.initListener('#edit-invoice', '#invoice-item-add', 'click', function (event) {
                Unicorn.initNewItem(++invoiceitemcount, 'invoice-items-container', 'invoice', itemoptions);
            });
            Unicorn.initListener('#invoice-items-container', '.item-list-selector', 'change', function (event, element) {
                Unicorn.retrieveItemTemplate("/{{ app('request')->route('company')->domain_name }}", element.siblings().find('.selected').attr('data-id'), element, Unicorn.setItemTemplate);
            });
            Unicorn.initListener('#edit-invoice', '#recurring-invoice-check', 'change', function (event, element) {
                if (element.prop('checked')) {
                    initRecurringElements('recurring-invoice-container');
                } else {
                    $('#recurring-invoice-container').html('');
                }
            });
            Unicorn.initListener('#edit-invoice', '#notify', 'change', function (event, element) {
                if (element.prop('checked')) {
                    $('#notify-details').val("on");
                } else {
                    $('#notify-details').val("off");
                }
            });

            $('#recurring-confirmation').on('click', '.recurring-invoice-update-btn', function (event) {
                $('#recurring-details').val($('input[name=recurring-details-selector]:checked').val());
                allowFormSubmission = true;
                form.submit();
            });

            function initChangeDetection(form) {
                for (let i=0; i<form.length; i++) {
                    let el = form[i];
                    el.dataset.origValue = el.value;
                }
            }
            function hasFormChanged(form) {
                for (let i=0; i<form.length; i++) {
                    let el = form[i];
                    if ('origValue' in el.dataset && el.dataset.origValue !== el.value) {
                        return true;
                    }
                }
                return false;
            }

            function initRecurringElements(elementId)
            {
                @if(is_null($recurrence))
                    let recurringelements = '<div class="input-field col s4 l2"> <input id="recurring-time-interval" name="recurring-time-interval" type="number" data-parsley-min="1" data-parsley-max="730" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('recurring-time-interval') or '1' }}"> <label for="recurring-time-interval" class="label-validation">Repeats every</label> <span class="helper-text"></span></div><div class="input-field col s8 l10"> <select id="recurring-time-period" name="recurring-time-period" data-parsley-required="true" data-parsley-trigger="change"><option value="day">Day</option><option value="week">Week</option><option value="month" selected>Month</option><option value="year">Year</option> </select> <label class="recurring-time-period"></label> <span class="helper-text"></span></div><div class="radio-field col s12"> <label id="rbtn-label" class="rbtn-label" for="recurring-until">Until</label><p> <label> <input id="recurring-until-never" name="recurring-until" type="radio" value="never" data-parsley-required="true" data-parsley-trigger="change"> <span>The End of Time</span> </label></p><p> <label> <input id="recurring-until-occurence" name="recurring-until" type="radio" value="occurence"> <span> After <input id="recurring-until-occurence-number" name="recurring-until-occurence-number" class="radio-input-inline radio-input-digit" type="number" data-parsley-required="false" data-parsley-min="1" data-parsley-max="730" data-parsley-trigger="change" value="{{ old('recurring-until-occurence-number') ?? '1' }}"> Occurences </span> </label></p><p> <label> <input id="recurring-until-date" name="recurring-until" type="radio" value="date"> <span> On <input id="recurring-until-date-value" name="recurring-until-date-value" class="datepicker radio-input-inline radio-input-date" type="text" data-parsley-required="false" data-parsley-trigger="change" value="{{ old('recurring-until-date-value') ?? \Carbon\Carbon::now()->addMonth(1)->format('j F, Y') }}"> </span> </label></p> <span class="helper-text manual-validation"></span></div>';
                    Unicorn.initDatepicker('#recurring-until-date-value', new Date("{{ Carbon\Carbon::now()->toDateTimeString() }}"), new Date("{{ Carbon\Carbon::now()->addYears(50)->toDateTimeString() }}").getFullYear(), new Date("{{ Carbon\Carbon::now()->addMonth()->toDateTimeString() }}"));
                @else
                    let recurringelements = '<div class="input-field col s4 l2"> <input id="recurring-time-interval" name="recurring-time-interval" type="number" data-parsley-min="1" data-parsley-max="730" data-parsley-required="true" data-parsley-trigger="change" value="{{ $recurrence->time_interval ?? '1' }}"> <label for="recurring-time-interval" class="label-validation">Repeats every</label> <span class="helper-text"></span></div><div class="input-field col s8 l10"> <select id="recurring-time-period" name="recurring-time-period" data-parsley-required="true" data-parsley-trigger="change"><option value="day" @if($recurrence->time_period == 'day') selected @endif>Day</option><option value="week" @if($recurrence->time_period == 'week') selected @endif>Week</option><option value="month" @if($recurrence->time_period == 'month') selected @endif selected>Month</option><option value="year" @if($recurrence->time_period == 'year') selected @endif>Year</option> </select> <label class="recurring-time-period"></label> <span class="helper-text"></span></div><div class="radio-field col s12"> <label id="rbtn-label" class="rbtn-label" for="recurring-until">Until</label><p> <label> <input id="recurring-until-never" name="recurring-until" type="radio" value="never" data-parsley-required="true" data-parsley-trigger="change" @if($recurrence->until_type == 'never') checked @endif> <span>The End of Time</span> </label></p><p> <label> <input id="recurring-until-occurence" name="recurring-until" type="radio" value="occurence" @if($recurrence->until_type == 'occurence') checked @endif> <span> After <input id="recurring-until-occurence-number" name="recurring-until-occurence-number" class="radio-input-inline radio-input-digit" type="number" data-parsley-required="false" data-parsley-min="1" data-parsley-max="730" data-parsley-trigger="change" @if($recurrence->until_type == 'occurence') value="{{ $recurrence->until_meta ?? '1' }}" @else value="1" @endif> Occurences </span> </label></p><p> <label> <input id="recurring-until-date" name="recurring-until" type="radio" value="date" @if($recurrence->until_type == 'date') checked @endif> <span> On <input id="recurring-until-date-value" name="recurring-until-date-value" class="datepicker radio-input-inline radio-input-date" type="text" data-parsley-required="false" data-parsley-trigger="change" @if($recurrence->until_type == 'date') value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $recurrence->until_meta)->toDateTimeString() ?? \Carbon\Carbon::now()->addMonth(1)->toDateTimeString() }}" @else value="{{ \Carbon\Carbon::now()->addMonth(1)->toDateTimeString() }}" @endif> </span> </label></p> <span class="helper-text manual-validation"></span></div>';
                    Unicorn.initDatepicker('#recurring-until-date-value', new Date("{{ Carbon\Carbon::now()->toDateTimeString() }}"), new Date("{{ Carbon\Carbon::now()->addYears(50)->toDateTimeString() }}").getFullYear(), new Date("@if($recurrence->until_type == 'date') {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $recurrence->until_meta)->toDateTimeString() ?? \Carbon\Carbon::now()->addMonth(1)->toDateTimeString() }} @else {{ \Carbon\Carbon::now()->addMonth(1)->toDateTimeString() }} @endif"));
                @endif
                Unicorn.initSelectize('#recurring-time-period');
                $('#' + elementId).append(recurringelements);
            }

            $('#invoice-items-container').on('click', '.invoice-item-delete-btn', function (event) {
                event.preventDefault();
                if(invoiceitemcount == 0)
                {
                    M.toast({ html: "Unable to delete the last invoice item", displayLength: "6000", classes: "error"});
                    return;
                }
                $('#delete-confirmation').modal('open');

                var itemid = $(this).attr('data-id');
                var count = $(this).attr('data-count');

                $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-id', itemid);
                $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-count', count);
            });

            $('#delete-confirmation').on('click', '.invoice-item-confirm-delete-btn', function (event) {
                event.preventDefault();

                var itemid = $(this).attr('data-id');
                var count = $(this).attr('data-count');

                if (typeof itemid !== typeof undefined && itemid !== false && itemid !== "false") {
                    var deleteinvoiceitemreq = $.ajax({
                        type: "DELETE",
                        url: "/{{ \App\Library\Poowf\Unicorn::getCompanyKey() }}/invoice/item/" + itemid + "/destroy",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                        .done(function (data) {
                            console.log(data);
                        })
                        .fail(function (jqXHR, textStatus) {
                            console.log(jqXHR);
                            console.log(textStatus);
                        })
                        .always(function () {
                            console.log("finish");
                        });
                }
                else {
                    $('#invoice_item_' + count).remove();
                }

                $.when(deleteinvoiceitemreq).done(function () {
                    $('#invoice_item_' + count).remove();
                    $('#delete-confirmation').modal('close');
                    $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-id', '');
                    $('#delete-confirmation').children().children('.invoice-item-confirm-delete-btn').attr('data-count', '');
                });
            });
        });
    </script>
@stop