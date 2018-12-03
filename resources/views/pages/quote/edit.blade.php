@extends("layouts.default", ['page_title' => 'Quote | Edit'])

@section("head")
    <link href="{{ mix('/assets/css/selectize.css') }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Edit Quote</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="edit-quote" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="nice_quote_id" name="nice_quote_id" type="text" data-parsley-required="true" data-parsley-trigger="change" data-parsley-minlength="4" data-parsley-pattern="/^[a-zA-Z0-9\-_]{0,40}$/" value="{{ $quote->nice_quote_id ?? '' }}" disabled>
                                <label for="nice_quote_id" class="label-validation">Quote ID</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <input id="date" name="date" class="datepicker" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $quote->date->format('j F, Y') ?? Carbon\Carbon::now()->format('j F, Y')  }}" placeholder="Date">
                                <label for="date" class="label-validation">Date</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="netdays" name="netdays" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ $quote->netdays ?? '' }}" placeholder="Net Days">
                                <label for="netdays" class="label-validation">Net Days</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="client_id" name="client_id" data-parsley-required="true" data-parsley-trigger="change" disabled>
                                    <option disabled="" selected="selected" value="">Pick a Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" @if($quote->client_id == $client->id) selected @endif>{{ $client->companyname ?? '' }}</option>
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
                            <a id="quote-item-add" class="btn-floating btn-large waves-effect waves-light red right"><i class="material-icons">add</i></a>
                        </div>
                    </div>
                    <div id="quote-items-container">
                        @foreach($quote->items as $key => $item)
                            <div id="quote_item_{{ $key }}" class="card-panel">
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
                                    <button data-id="{{ $item->id }}"  data-count="{{ $key }}" class="quote-item-delete-btn btn waves-effect waves-light col s12 m3 offset-m9 red">Delete</button>
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
            let quoteitemcount = {{ ($quote->items()->count() - 1) ?? 0 }};

            Unicorn.initParsleyValidation('#edit-quote');

            $('.trumbowyg-textarea').trumbowyg({
                svgPath: '/assets/fonts/trumbowygicons.svg',
                removeformatPasted: true,
                resetCss: true,
                autogrow: true,
            });

            $('.datepicker').datepicker({
                autoClose: 'false',
                format: 'd mmmm, yyyy',
                yearRange: [1950, {{ \Carbon\Carbon::now()->addYear()->format('Y') }}],
                defaultDate: new Date("{{ $quote->date ?? Carbon\Carbon::now()->toDateTimeString()  }}"),
                setDefaultDate: true,
                onSelect: function() {
                    // var date = $(this)[0].formats.yyyy() + '-' + $(this)[0].formats.mm() + '-' + $(this)[0].formats.dd()
                    // $('#receiveddate').val(date);
                }
            });

            Unicorn.initSelectize('#client_id');
            $('.modal').modal();

            $('#quote-item-add').on('click', function() {
                initQuoteItem(++quoteitemcount, 'quote-items-container');
            });

            function initQuoteItem(count, elementid) {
                let quoteitem = '<div id="quote_item_' + count + '" class="card-panel"><div class="row"><div class="input-field col s12 l8"> <input id="item_name" name="item_name[]" type="text" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Name"> <label for="item_name" class="label-validation">Name</label> <span class="helper-text"></span></div><div class="input-field col s6 l2"> <input id="item_quantity" name="item_quantity[]" type="number" data-parsley-required="true" data-parsley-trigger="change" data-parsley-min="1" placeholder="Item Quantity"> <label for="item_quantity" class="label-validation">Quantity</label> <span class="helper-text"></span></div><div class="input-field col s6 l2"> <input id="item_price" name="item_price[]" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Price "> <label for="item_price" class="label-validation">Price</label> <span class="helper-text"></span></div><div class="input-field col s12 mtop30"><textarea id="item_description" name="item_description[]" class="trumbowyg-textarea" data-parsley-required="false" data-parsley-trigger="change" placeholder="Item Description"></textarea><label for="item_description" class="label-validation">Description</label> <span class="helper-text"></span></div></div><div class="row"> <button data-id="false" data-count="' + count + '" class="quote-item-delete-btn btn waves-effect waves-light col s12 m3 offset-m9 red">Delete</button></div></div>';
                $('#' + elementid).append(quoteitem);
                $('.trumbowyg-textarea').trumbowyg({
                    svgPath: '/assets/fonts/trumbowygicons.svg',
                    removeformatPasted: true,
                    resetCss: true,
                    autogrow: true,
                });
                $('html, body').animate({
                    scrollTop: $("#quote_item_" + count).offset().top
                }, 500, 'linear');
            }

            $('#quote-items-container').on('click', '.quote-item-delete-btn', function (event) {
                event.preventDefault();
                if(quoteitemcount == 0)
                {
                    M.toast({ html: "Unable to delete the last invoice item", displayLength: "6000", classes: "error"});
                    return;
                }
                $('#delete-confirmation').modal('open');

                var itemid = $(this).attr('data-id');
                var count = $(this).attr('data-count');

                $('#delete-confirmation').children().children('.quote-item-confirm-delete-btn').attr('data-id', itemid);
                $('#delete-confirmation').children().children('.quote-item-confirm-delete-btn').attr('data-count', count);
            });

            $('#delete-confirmation').on('click', '.quote-item-confirm-delete-btn', function (event) {
                event.preventDefault();

                var itemid = $(this).attr('data-id');
                var count = $(this).attr('data-count');

                if (typeof itemid !== typeof undefined && itemid !== false && itemid !== "false") {
                    var deletequoteitemreq = $.ajax({
                        type: "DELETE",
                        url: "/quote/item/" + itemid + "/destroy",
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
                    $('#quote_item_' + count).remove();
                }

                $.when(deletequoteitemreq).done(function () {
                    $('#quote_item_' + count).remove();
                    $('#delete-confirmation').modal('close');
                    $('#delete-confirmation').children().children('.quote-item-confirm-delete-btn').attr('data-id', '');
                    $('#delete-confirmation').children().children('.quote-item-confirm-delete-btn').attr('data-count', '');
                });
            });
        });
    </script>
@stop