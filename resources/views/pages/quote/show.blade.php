@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>
        :root .card.single-history {
            overflow: hidden;
            margin: 0px 20px;
            padding: 35px;
            text-align: center;
        }

        .single-history-wrapper {
        }

    </style>
    <link href="{{ mix('/assets/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
@stop

@section("content")
    <div class="mini-container">
        <div id="top-action-container" class="row desktop-only">
            <div class="col s12 mtop30 right">
                <a href="#" data-id="{{ $quote->id }}" class="quote-share-btn btn btn-link waves-effect waves-dark">
                    Share
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('quote.download', [ 'quote' => $quote->id] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('quote.printview', [ 'quote' => $quote->id] ) }}">
                    Print
                </a>
            </div>
        </div>
        <div id="quote-action-container" class="row mbtm0 desktop-only">
            <div class="col s12 right">
                <form method="post" action="{{ route('quote.convert', [ 'quote' => $quote->id ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn btn-link waves-effect waves-dark null-btn" type="submit">Convert to Invoice</button>
                </form>
                <form method="post" action="{{ route('quote.duplicate', [ 'quote' => $quote->id ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn blue darken-3 waves-effect waves-dark null-btn" type="submit">Clone</button>
                </form>
                <form method="post" action="{{ route('quote.archive', [ 'quote' => $quote->id ] ) }}" class="null-form">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <button class="btn amber darken-2 waves-effect waves-dark null-btn" type="submit">Archive</button>
                </form>
                <a href="{{ route('quote.edit', [ 'quote' => $quote->id ] ) }}" class="btn light-blue waves-effect waves-dark">
                    Edit
                </a>
                <a href="#" data-id="{{ $quote->id }}" class="quote-delete-btn btn red waves-effect waves-dark">
                    Delete
                </a>
            </div>
        </div>
        <div class="fixed-action-btn toolbar mobile-only">
            <a class="btn-floating btn-large btn-large red">
                <i class="large material-icons">menu</i>
            </a>
            <ul>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Quote">
                    <form method="post" action="{{ route('quote.duplicate', [ 'quote' => $quote->id ] ) }}" class="null-form">
                        {{ csrf_field() }}
                        <button class="btn blue darken-3 waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">control_point_duplicate</i>
                        </button>
                    </form>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Archive Quote">
                    <form method="post" action="{{ route('quote.archive', [ 'quote' => $quote->id ] ) }}" class="null-form">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                        <button class="btn amber darken-2 waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">archive</i>
                        </button>
                    </form>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Quote">
                    <a href="{{ route('quote.edit', [ 'quote' => $quote->id ] ) }}" class="btn light-blue waves-effect waves-dark">
                        <i class="material-icons">edit</i>
                    </a>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Quote">
                    <a href="#" data-id="{{ $quote->id }}" class="quote-delete-btn btn red waves-effect waves-dark">
                        <i class="material-icons">delete</i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col s12 l4">
                <h3>Details</h3>
                <div id="details-panel" class="card-panel">
                    <dl>
                        <dt>Company Name</dt>
                        <dd>{{ $client->companyname }}</dd>
                        <dt>Company Block</dt>
                        <dd>{{ $client->block ?? '-' }}</dd>
                        <dt>Company Street</dt>
                        <dd>{{ $client->street ?? '-' }}</dd>
                        <dt>Company Unit Number</dt>
                        <dd>{{ $client->unitnumber ?? '-' }}</dd>
                        <dt>Company Postal Code</dt>
                        <dd>{{ $client->postalcode ?? '-' }}</dd>
                        <dt>Company Nickname</dt>
                        <dd>{{ $client->nickname ?? '-' }}</dd>
                        <dt>Company Registration Number</dt>
                        <dd>{{ $client->crn }}
                        <dt>Contact Name</dt>
                        <dd>{{ $client->contactname ?? '-' }}</dd>
                        <dt>Contact Email</dt>
                        <dd>{{ $client->contactemail ?? '-' }}</dd>
                        <dt>Contact Phone</dt>
                        <dd>{{ $client->contactphone ?? '-' }}</dd>
                        <dt>Status</dt>
                        <dd>
                            @if ($quote->status == App\Models\Quote::STATUS_DRAFT)
                                <span class="alt-badge">{{ $quote->statustext() }}</span>
                            @elseif ($quote->status == App\Models\Quote::STATUS_OPEN)
                                <span class="alt-badge warning">{{ $quote->statustext() }}</span>
                            @elseif ($quote->status == App\Models\Quote::STATUS_EXPIRED)
                                <span class="alt-badge error">{{ $quote->statustext() }}</span>
                            @elseif ($quote->status == App\Models\Quote::STATUS_COMPLETED)
                                <span class="alt-badge success">{{ $quote->statustext() }}</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="col s12 l8">
                <h3>Quote</h3>
                <div class="quote" style="background-color: #ffffff; padding: 50px 50px 20px; color: #8c8c8c;">
                    <div class="row quote-header" style="position: relative; margin-bottom: 160px;">
                        <div class="col-xs-7" style="position: absolute; left: 0; padding: 0 15px;">
                            <div class="quote-logo" style="height: 110px; min-width: 210px; background-image: url('{{ \App\Library\Poowf\Unicorn::getStorageFile($quote->company->logo, [210, 110]) }}'); background-repeat: no-repeat; background-position: 0; background-size: contain;"></div>
                        </div>
                        <div class="col-xs-5 quote-order" style="position: absolute; right: 0; padding: 0 15px; text-align: left;">
                            <span class="quote-id" style="display: block; font-size: 30px; line-height: 30px; margin-bottom: 10px;">Quote #{{ $quote->nice_quote_id }}</span>
                            <span class="quote-date" style="display: block; font-size: 18px; line-height: 30px; text-align: right;">Quote Date: {{ $quote->date }}</span>
                            <span class="quote-duedate" style="display: block; font-size: 18px; line-height: 30px; text-align: right;">Quote Expires: {{ $quote->duedate }}</span>
                        </div>
                    </div>
                    <div class="row quote-data" style="position: relative; margin-bottom: 320px;">
                        <div class="col-xs-5 quote-person" style="position: absolute; left: 0; padding: 0 15px; ">
                            <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">Prepared For: </span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $quote->client->companyname }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">@if($quote->client->block){{ $quote->client->block }} @endif {{ $quote->client->street ?? 'No Street' }}</span>
                            @if($quote->client->unitnumber)<span style="font-size: 18px; line-height: 26px; display: block;">#{{ $quote->client->unitnumber }}</span>@endif
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $quote->client->country_code ?? 'No Country' }} {{ $quote->client->postalcode ?? 'No Postal Code' }}</span>
                        </div>
                        <div class="col-xs-2 quote-payment-direction" style="position: absolute; padding-top: 10px; left: 0; right:0; text-align: center;">
                            <img src="{{ asset('/assets/img/lefttoright.png') }}" width="80" height="80" />
                        </div>
                        <div class="col-xs-5 quote-person" style="position: absolute; right: 0; padding: 0 15px; text-align: left;">
                            <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">{{ $quote->company->name ?? 'No Company Name' }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">{{ $quote->company->crn ?? 'No Company Registration Number' }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $quote->company->owner->full_name ?? 'No Company Owner Name' }}</span>
                            @if($quote->company->address)
                                <span style="font-size: 18px; line-height: 26px; display: block;">@if($quote->company->address->block){{ $quote->company->address->block }} @endif {{ $quote->company->address->street ?? 'No Street' }}</span>
                                @if($quote->company->address->unitnumber)<span style="font-size: 18px; line-height: 26px; display: block;">#{{ $quote->company->address->unitnumber }}</span>@endif
                                <span style="font-size: 18px; line-height: 26px; display: block;">{{ $quote->company->address->postalcode ?? 'No Postal Code' }}</span>
                            @else
                                <span style="font-size: 18px; line-height: 26px; display: block;">{{ $quote->company->owner->email ?? 'No Company Owner Email' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"
                             style="position: relative; padding: 0 15px;">
                            <table class="quote-details" style="border-collapse: collapse; border-spacing: 0; background-color: transparent; width: 100%; font-size: 16px;">
                                <tbody>
                                    <tr>
                                        <th style="padding: 0; padding-bottom: 8px; border-bottom: 1px solid #f0f0f0; text-align: left; width: 60%;">
                                            Description
                                        </th>
                                        <th style="padding: 0; text-align: right; padding-bottom: 8px; border-bottom: 1px solid #f0f0f0; width: 17%;" class="hours">
                                            Quantity
                                        </th>
                                        <th style="padding: 0; text-align: right; padding-bottom: 8px; border-bottom: 1px solid #f0f0f0; width: 15%;" class="amount">
                                            Amount
                                        </th>
                                    </tr>
                                    @foreach($quote->items as $key => $item)
                                        <tr>
                                            <td class="description" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0;">
                                                <span style="display: block; font-weight: 700;">{{ $item->name }}</span>
                                                {!! $item->description !!}
                                            </td>
                                            <td class="quantity" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; text-align: right;">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="amount" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; text-align: right;">
                                                ${{ $item->moneyformatprice() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td style="padding: 20px 0;"></td>
                                        <td class="summary" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; color: #aaaaaa;">
                                            Subtotal
                                        </td>
                                        <td class="amount" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; text-align: right;">
                                            ${{ $quote->calculatesubtotal() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 0;"></td>
                                        <td class="summary" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; color: #aaaaaa;">
                                            Tax ({{ $quote->company->settings->tax ?? 0 }}%)
                                        </td>
                                        <td class="amount" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; text-align: right;">
                                            ${{ $quote->calculatetax() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 0;"></td>
                                        <td class="summary total" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; color: #8c8c8c; font-weight: 700;">
                                            Total
                                        </td>
                                        <td class="amount total-value" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; text-align: right; font-size: 22px; color: #4da6a6;">
                                            ${{ $quote->calculatetotal() }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 quote-message" style="position: relative; padding: 0 15px; font-size: 16px; margin-bottom: 62px;">
                            <span class="title" style="font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 12px;">Terms & Conditions</span>
                            {!! $quote->company->settings->quote_conditions !!}
                        </div>
                    </div>
                    <div class="row quote-company-info" style="margin-bottom: 70px;">
                        <div class="logo" style="position: relative; display: block; width: 100%;  text-align: center;">
                            <img src="{{ \App\Library\Poowf\Unicorn::getStorageFile($quote->company->smlogo, [100,100]) }}" alt="Logo-symbol" width="100" height="100" style="border: 0; vertical-align: middle;">
                        </div>
                        <div style="margin-top: 20px;">
                            <div class="row">
                                <div class="col s6 m4 summary" style="display: inline-block; padding: 0 15px; line-height: 16px; text-align: center;">
                                    <span class="title" style="color: #8c8c8c; font-size: 14px; line-height: 21px; font-weight: 700;">{{ $quote->company->name ?? 'No Company Name' }}</span>
                                    <p style="font-size: inherit; margin: 0 0 15px; line-height: 16px;"></p>
                                </div>
                                <div class="col s6 m4 phone" style="display: inline-block; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                                    <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                                        <li> {{ $quote->company->phone ?? 'No Phone Number' }}</li>
                                    </ul>
                                </div>
                                <div class="col s6 m4 email" style="display: inline-block; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                                    <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                                        <li>{{ $quote->company->email ?? 'No Email' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row quote-footer" style="text-align: center;">
                        <div class="col s12" style="position: relative; padding: 0 15px;">
                            <a class="btn btn-lg btn-space btn-default" style="-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); border: 1px solid transparent; color: #404040; background-color: #fff; border-color: #dedede; padding: 0 12px; line-height: 38px; border-radius: 3px; font-weight: 700; margin-right: 5px; margin-bottom: 5px; min-width: 96px; font-size: 14px;" href="{{ route('quote.download', [ 'quote' => $quote->id] ) }}">
                                Save PDF
                            </a>
                            <a class="btn btn-lg btn-space btn-default" style="-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); border: 1px solid transparent; color: #404040; background-color: #fff; border-color: #dedede; padding: 0 12px; line-height: 38px; border-radius: 3px; font-weight: 700; margin-right: 5px; margin-bottom: 5px; min-width: 96px; font-size: 14px;" href="{{ route('quote.printview', [ 'quote' => $quote->id] ) }}">
                                Print
                            </a>
                            {{--
                            <a class="btn btn-lg btn-space btn-primary" style="-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); border: 1px solid transparent; color: #fff; background-color: #4da6a6; border-color: #4da6a6; padding: 0 12px; line-height: 38px; border-radius: 3px; font-weight: 700; margin-right: 5px; margin-bottom: 5px; min-width: 96px; font-size: 14px;">
                                Pay now
                            </a>
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Delete Quote?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-quote-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal quote-confirm-delete-btn" type="submit">Delete</button>
            </form>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>

    <div id="shared-details" class="modal">
        <div class="modal-content">
            <div class="left">
                <h5>Share</h5>
            </div>
            <div class="right">
                <a href="#" data-id="{{ $quote->id }}" class="quote-regenerate-btn btn btn-link waves-effect waves-dark">
                    Regenerate Link
                </a>
            </div>
            <input id="shared-link" type="text" value="{{ route('quote.token', [ 'token' => $quote->share_token] ) }}">
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {

            $('#change-history-container').on('init', function(event, slick, direction){
                let height = $('#details-panel').outerHeight();
                console.log($(this).find('.single-history').css('height', height));
                // left
            });

            $('#top-action-container').on('click', '.quote-share-btn', function (event) {
                event.preventDefault();
                if($('#shared-link').val() == "")
                {
                    getSharedLink();
                }
                else
                {
                    $('#shared-details').modal('open');
                }
            });

            $('#shared-details').on('click', '.quote-regenerate-btn', function (event) {
                event.preventDefault();
                getSharedLink();
            });


            $('#quote-action-container').on('click', '.quote-delete-btn', function (event) {
                event.preventDefault();
                let quoteid = $(this).attr('data-id');
                $('#delete-quote-form').attr('action', '/quote/' + quoteid + '/destroy');
                $('#delete-confirmation').modal('open');
            });

            $('.fixed-action-btn').on('click', '.quote-delete-btn', function (event) {
                event.preventDefault();
                let quoteid = $(this).attr('data-id');
                $('#delete-quote-form').attr('action', '/quote/' + quoteid + '/destroy');
                $('#delete-confirmation').modal('open');
            });

            $('#change-history-container').slick({
                // normal options...
                infinite: false,
                arrows: false,
                dots: true,
                slidesToShow: 2,
                slidesToScroll: 2,
                adaptiveHeight: false,
                responsive: [
                    {
                        breakpoint: 1750,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 993,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });

            function getSharedLink()
            {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "PATCH",
                    url: "{{ route('quote.share', [ 'quote' => $quote->id]) }}",
                })
                .done(function(data) {
                    $('#shared-link').val("{{ route('quote.token') }}?token=" + data);
                    $('#shared-details').modal('open');
                });
            }
        });
    </script>
@stop