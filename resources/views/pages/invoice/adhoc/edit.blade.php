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
                            <div class="input-field col s12 m6">
                                <input id="companyname" name="companyname" type="text" data-parsley-required="true"  data-parsley-trigger="change" data-parsley-minlength="4" value="{{ $client->companyname ?? '' }}" placeholder="Client Company Name">
                                <label for="companyname" class="label-validation">Client Company Name</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <select id="country_code" name="country_code" data-parsley-trigger="change" placeholder="Client Country">
                                    @foreach($countries as $country)
                                        <option value="{{ $country['iso_3166_1_alpha2'] }}" @if($client->country_code == $country['iso_3166_1_alpha2']) selected @endif>{{ $country['name']['common'] }}</option>
                                    @endforeach
                                </select>
                                <label for="country" class="label-validation">Client Country</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="block" name="block" type="text" data-parsley-trigger="change" value="{{ $client->block ?? '' }}" placeholder="Client Block">
                                <label for="block" class="label-validation">Client Block</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6">
                                <input id="street" name="street" type="text" data-parsley-trigger="change" value="{{ $client->street ?? '' }}" placeholder="Client Street">
                                <label for="street" class="label-validation">Client Street</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <i class="mdi mdi-pound prefix-inline"></i>
                                <input id="unitnumber" name="unitnumber" type="text" data-parsley-trigger="change" value="{{ $client->unitnumber ?? '' }}" placeholder="Client Unit Number">
                                <label for="unitnumber" class="label-validation">Client Unit Number</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6">
                                <input id="postalcode" name="postalcode" type="text" data-parsley-trigger="change" value="{{ $client->postalcode ?? '' }}" placeholder="Client Postal Code">
                                <label for="postalcode" class="label-validation">Client Postal Code</label>
                                <span class="helper-text"></span>
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
            let invoiceitemcount = {{ ($invoice->items()->count() - 1) ?? 0 }};
            let form = document.getElementById('edit-invoice');

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

            $('#country_code').selectize({});

            $('#invoice-item-add').on('click', function() {
                initInvoiceItem(++invoiceitemcount, 'invoice-items-container');
            });

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
                });
        });
    </script>
@stop