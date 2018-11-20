@extends("layouts.default", ['page_title' => 'Invoice | Edit'])

@section("head")
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
                                    <label class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Automatically send Invoice to customers on Invoice Date">
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
                                        <input id="recurring-invoice-check" name="recurring-invoice-check" type="checkbox" @if($invoice->event) checked @endif>
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
                                        <textarea id="item_description" name="item_description[]" class="trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description">{{ $item->description ?? '' }}</textarea>
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
            @if($invoice->event) initRecurringElements('recurring-invoice-container'); @endif
            let invoiceitemcount = {{ ($invoice->items()->count() - 1) ?? 0 }};
            let form = document.getElementById('edit-invoice');
            let allowFormSubmission = @if(is_null($event)){{ 'true' }}@else{{ 'false' }}@endif;

            @if(!is_null($event))initChangeDetection(form);@endif

            // Unicorn.initParsleyValidation('#edit-invoice');

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
                defaultDate: new Date("{{ $invoice->date ?? Carbon\Carbon::now()->toDateTimeString()  }}"),
                setDefaultDate: true,
                onSelect: function() {
                    // var date = $(this)[0].formats.yyyy() + '-' + $(this)[0].formats.mm() + '-' + $(this)[0].formats.dd()
                    // $('#receiveddate').val(date);
                }
            });

            $('#client_id').selectize();

            $('#invoice-item-add').on('click', function() {
                initInvoiceItem(++invoiceitemcount, 'invoice-items-container');
            });

            $('#edit-invoice').on('change', '#recurring-invoice-check', function (event) {
                if ($(this).prop('checked')) {
                    initRecurringElements('recurring-invoice-container');
                } else {
                    $('#recurring-invoice-container').html('');
                }
            });

            $('#edit-invoice').on('change', '#notify', function (event) {
                if ($(this).prop('checked')) {
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

            function initRecurringElements(elementid)
            {
                @if(is_null($event))
                    let recurringelements = '<div class="input-field col s4 l2"> <input id="recurring-time-interval" name="recurring-time-interval" type="number" data-parsley-min="1" data-parsley-max="730" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('recurring-time-interval') or '1' }}"> <label for="recurring-time-interval" class="label-validation">Repeats every</label> <span class="helper-text"></span></div><div class="input-field col s8 l10"> <select id="recurring-time-period" name="recurring-time-period" data-parsley-required="true" data-parsley-trigger="change"><option value="day">Day</option><option value="week">Week</option><option value="month" selected>Month</option><option value="year">Year</option> </select> <label class="recurring-time-period"></label> <span class="helper-text"></span></div><div class="radio-field col s12"> <label id="rbtn-label" class="rbtn-label" for="recurring-until">Until</label><p> <label> <input id="recurring-until-never" name="recurring-until" type="radio" value="never" data-parsley-required="true" data-parsley-trigger="change"> <span>The End of Time</span> </label></p><p> <label> <input id="recurring-until-occurence" name="recurring-until" type="radio" value="occurence"> <span> After <input id="recurring-until-occurence-number" name="recurring-until-occurence-number" class="radio-input-inline radio-input-digit" type="number" data-parsley-required="false" data-parsley-min="1" data-parsley-max="730" data-parsley-trigger="change" value="{{ old('recurring-until-occurence-number') ?? '1' }}"> Occurences </span> </label></p><p> <label> <input id="recurring-until-date" name="recurring-until" type="radio" value="date"> <span> On <input id="recurring-until-date-value" name="recurring-until-date-value" class="datepicker radio-input-inline radio-input-date" type="text" data-parsley-required="false" data-parsley-trigger="change" value="{{ old('recurring-until-date-value') ?? \Carbon\Carbon::now()->addMonth(1)->format('j F, Y') }}"> </span> </label></p> <span class="helper-text manual-validation"></span></div>';
                    $('#' + elementid).append(recurringelements);
                    $('#recurring-time-period').selectize();

                    $('#recurring-until-date-value').datepicker({
                        autoClose: 'false',
                        format: 'd mmmm, yyyy',
                        yearRange: [{{ \Carbon\Carbon::now()->format('Y') }}, {{ \Carbon\Carbon::now()->addYears(50)->format('Y') }}],
                        onSelect: function() {
                            // let date = $(this)[0].formats.yyyy() + '-' + $(this)[0].formats.mm() + '-' + $(this)[0].formats.dd()
                            // $('#receiveddate').val(date);
                        }
                    });
                @else
                    let recurringelements = '<div class="input-field col s4 l2"> <input id="recurring-time-interval" name="recurring-time-interval" type="number" data-parsley-min="1" data-parsley-max="730" data-parsley-required="true" data-parsley-trigger="change" value="{{ $event->time_interval ?? '1' }}"> <label for="recurring-time-interval" class="label-validation">Repeats every</label> <span class="helper-text"></span></div><div class="input-field col s8 l10"> <select id="recurring-time-period" name="recurring-time-period" data-parsley-required="true" data-parsley-trigger="change"><option value="day" @if($event->time_period == 'day') selected @endif>Day</option><option value="week" @if($event->time_period == 'week') selected @endif>Week</option><option value="month" @if($event->time_period == 'month') selected @endif selected>Month</option><option value="year" @if($event->time_period == 'year') selected @endif>Year</option> </select> <label class="recurring-time-period"></label> <span class="helper-text"></span></div><div class="radio-field col s12"> <label id="rbtn-label" class="rbtn-label" for="recurring-until">Until</label><p> <label> <input id="recurring-until-never" name="recurring-until" type="radio" value="never" data-parsley-required="true" data-parsley-trigger="change" @if($event->until_type == 'never') checked @endif> <span>The End of Time</span> </label></p><p> <label> <input id="recurring-until-occurence" name="recurring-until" type="radio" value="occurence" @if($event->until_type == 'occurence') checked @endif> <span> After <input id="recurring-until-occurence-number" name="recurring-until-occurence-number" class="radio-input-inline radio-input-digit" type="number" data-parsley-required="false" data-parsley-min="1" data-parsley-max="730" data-parsley-trigger="change" @if($event->until_type == 'occurence') value="{{ $event->until_meta ?? '1' }}" @else value="1" @endif> Occurences </span> </label></p><p> <label> <input id="recurring-until-date" name="recurring-until" type="radio" value="date" @if($event->until_type == 'date') checked @endif> <span> On <input id="recurring-until-date-value" name="recurring-until-date-value" class="datepicker radio-input-inline radio-input-date" type="text" data-parsley-required="false" data-parsley-trigger="change" @if($event->until_type == 'date') value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->until_meta)->toDateTimeString() ?? \Carbon\Carbon::now()->addMonth(1)->toDateTimeString() }}" @else value="{{ \Carbon\Carbon::now()->addMonth(1)->toDateTimeString() }}" @endif> </span> </label></p> <span class="helper-text manual-validation"></span></div>';

                    $('#' + elementid).append(recurringelements);
                    $('#recurring-time-period').selectize();

                    $('#recurring-until-date-value').datepicker({
                        autoClose: 'false',
                        format: 'd mmmm, yyyy',
                        yearRange: [{{ \Carbon\Carbon::now()->format('Y') }}, {{ \Carbon\Carbon::now()->addYears(50)->format('Y') }}],
                        defaultDate: new Date("@if($event->until_type == 'date') {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->until_meta)->toDateTimeString() ?? \Carbon\Carbon::now()->addMonth(1)->toDateTimeString() }} @else {{ \Carbon\Carbon::now()->addMonth(1)->toDateTimeString() }} @endif"),
                        setDefaultDate: true,
                        onSelect: function() {
                            // let date = $(this)[0].formats.yyyy() + '-' + $(this)[0].formats.mm() + '-' + $(this)[0].formats.dd()
                            // $('#receiveddate').val(date);
                        }
                    });
                @endif
            }

            function initInvoiceItem(count, elementid) {
                let invoiceitem = '<div id="invoice_item_' + count + '" class="card-panel"><div class="row"><div class="input-field col s12 l8"> <input id="item_name" name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Name"> <label for="item_name" class="label-validation">Name</label> <span class="helper-text"></span></div><div class="input-field col s6 l2"> <input id="item_quantity" name="item_quantity[]" type="number" data-parsley-required="true" data-parsley-trigger="change" data-parsley-min="1" placeholder="Item Quantity"> <label for="item_quantity" class="label-validation">Quantity</label> <span class="helper-text"></span></div><div class="input-field col s6 l2"> <input id="item_price" name="item_price[]" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Price"> <label for="item_price" class="label-validation">Price</label> <span class="helper-text"></span></div><div class="input-field col s12 mtop30"><textarea id="item_description" name="item_description[]" class="trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description"></textarea><label for="item_description" class="label-validation">Description</label> <span class="helper-text"></span></div></div><div class="row"> <button data-id="false" data-count="' + count + '" class="invoice-item-delete-btn btn waves-effect waves-light col s12 m3 offset-m9 red">Delete</button></div></div>';
                $('#' + elementid).append(invoiceitem);
                $('.trumbowyg-textarea').trumbowyg({
                    svgPath: '/assets/fonts/trumbowygicons.svg',
                    removeformatPasted: true,
                    resetCss: true,
                    autogrow: true,
                });
                $('html, body').animate({
                    scrollTop: $("#invoice_item_" + count).offset().top
                }, 500, 'linear');
            }

            $('#invoice-items-container').on('click', '.invoice-item-delete-btn', function (event) {
                event.preventDefault();
                if(invoiceitemcount == 0)
                {
                    M.toast({ html: "Unable to delete the last invoice item", displayLength: "poowf", classes: "error"});
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
                        url: "/invoice/item/" + itemid + "/destroy",
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

            $('#edit-invoice').parsley({
                successClass: 'valid',
                errorClass: 'invalid',
                errorsContainer: function (velem) {
                    let $errelem = velem.$element.siblings('span.helper-text');
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
                    }
                })
                .on('field:error', function(velem) {
                    if (velem.$element.is('select')) {
                        velem.$element.siblings('.selectize-control').removeClass('valid').addClass('invalid');
                    }
                })
                .on('form:submit', function(velem) {
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
        });
    </script>
@stop