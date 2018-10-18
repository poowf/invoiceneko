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
        <div id="top-action-container" class="row">
            <div class="col s12 mtop30 right">
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('payment.create', [ 'invoice' => $invoice->id] ) }}">
                    Log Payment
                </a>
                <a href="#" data-id="{{ $invoice->id }}" class="invoice-share-btn btn btn-link waves-effect waves-dark">
                    Share
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('invoice.download', [ 'invoice' => $invoice->id] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('invoice.printview', [ 'invoice' => $invoice->id] ) }}">
                    Print
                </a>
            </div>
        </div>
        <div id="invoice-action-container" class="row" style="margin-bottom: 0;">
            <div class="col s12 right">
                <form method="post" action="{{ route('invoice.convert', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn btn-link waves-effect waves-dark null-btn" type="submit">Convert back to Quote</button>
                </form>
                <form method="post" action="{{ route('invoice.duplicate', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn blue darken-3 waves-effect waves-dark null-btn" type="submit">Clone</button>
                </form>
                <form method="post" action="{{ route('invoice.writeoff', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <button class="btn deep-orange darken-2 waves-effect waves-dark null-btn" type="submit">Write Off</button>
                </form>
                <form method="post" action="{{ route('invoice.archive', [ 'invoice' => $invoice->id ] ) }}" class="null-form">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <button class="btn amber darken-2 waves-effect waves-dark null-btn" type="submit">Archive</button>
                </form>
                <a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}" class="btn light-blue waves-effect waves-dark">
                    Edit
                </a>
                <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn btn red waves-effect waves-dark">
                    Delete
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col s12 l4">
                <h3>Details</h3>
                <div id="details-panel" class="card-panel">
                    <dt>Company Name</dt>
                    <dd>{{ $client->companyname }}</dd>
                    <dt>Company Block</dt>
                    <dd>{{ $client->block or '-' }}</dd>
                    <dt>Company Street</dt>
                    <dd>{{ $client->street or '-' }}</dd>
                    <dt>Company Unit Number</dt>
                    <dd>{{ $client->unitnumber or '-' }}</dd>
                    <dt>Company Postal Code</dt>
                    <dd>{{ $client->postalcode or '-' }}</dd>
                    <dt>Company Nickname</dt>
                    <dd>{{ $client->nickname or '-' }}</dd>
                    <dt>Company Registration Number</dt>
                    <dd>{{ $client->crn }}
                    <dt>Contact Name</dt>
                    <dd>{{ $client->contactname or '-' }}</dd>
                    <dt>Contact Email</dt>
                    <dd>{{ $client->contactemail or '-' }}</dd>
                    <dt>Contact Phone</dt>
                    <dd>{{ $client->contactphone or '-' }}</dd>
                    <dt>Status</dt>
                    <dd>
                        @if ($invoice->status == App\Models\Invoice::STATUS_OVERDUE)
                            <span class="alt-badge error">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_DRAFT)
                            <span class="alt-badge">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_OPEN)
                            <span class="alt-badge warning">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_CLOSED)
                            <span class="alt-badge success">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_ARCHIVED)
                            <span class="alt-badge grey">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == App\Models\Invoice::STATUS_WRITTENOFF)
                            <span class="alt-badge grey">{{ $invoice->statustext() }}</span>
                        @endif
                    </dd>
                    </dl>
                </div>
                <h3>Change History</h3>
                <div id="change-history-container" class="change-history-container">
                    <div class="single-history-wrapper">
                        <div class="card single-history">
                            <h6>Date/Time</h6>
                            <p class="mtop20">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->updated_at)->format('j F, Y') }}</p>
                            <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->updated_at)->format('h:i:s a') }}</p>
                            <span class="alt-badge info mtop20">Current Version</span>
                            <a href="{{ route('invoice.edit', [ 'invoice' => $invoice->id ] ) }}" class="btn btn-theme full-width mtop20">Edit</a>
                        </div>
                    </div>
                    @foreach($histories as $key => $history)
                        <div class="single-history-wrapper">
                            <div class="card single-history">
                                <h6>Date/Time</h6>
                                <p class="mtop20">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $history->updated_at)->format('j F, Y') }}</p>
                                <p>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $history->updated_at)->format('h:i:s a') }}</p>
                                <span class="alt-badge warning mtop20">Past Version</span>
                                <a href="{{ route('invoice.old.show', [ 'oldinvoice' => $history->id ] ) }}" class="btn btn-theme full-width mtop20">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <h3>Payment History</h3>
                <div id="payment-history-container" class="payment-history-container">
                    <div class="card-panel">
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Mode</th>
                                    <th>Amount</th>
                                    <th>Percentage of Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $key => $payment)
                                    <tr>
                                        <td>{{ $payment->date_format }}</td>
                                        <td>{{ $payment->mode }}</td>
                                        <td>S${{ $payment->money_format }}</td>
                                        <td><span class="alt-badge teal lighten-1">{{ $payment->percentage }}%</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col s12 l8">
                <h3>Invoice</h3>
                <div class="invoice" style="background-color: #ffffff; padding: 50px 50px 20px; color: #8c8c8c;">
                    <div class="row invoice-header" style="position: relative; margin-bottom: 160px;">
                        <div class="col-xs-7" style="position: absolute; left: 0; padding: 0 15px;">
                            <div class="invoice-logo" style="height: 110px; min-width: 210px; background-image: url('{{ $invoice->company->logo }}'); background-repeat: no-repeat; background-position: 0;"></div>
                        </div>
                        <div class="col-xs-5 invoice-order" style="position: absolute; right: 0; padding: 0 15px; text-align: left;">
                            <span class="invoice-id" style="display: block; font-size: 30px; line-height: 30px; margin-bottom: 10px;">Invoice #{{ $invoice->nice_invoice_id }}</span>
                            <span class="invoice-date" style="display: block; font-size: 18px; line-height: 30px; text-align: right;">Invoice Date: {{ $invoice->date }}</span>
                            <span class="invoice-duedate" style="display: block; font-size: 18px; line-height: 30px; text-align: right;">Payment Due: {{ $invoice->duedate }}</span>
                            <span class="invoice-netdays" style="display: block; font-size: 18px; line-height: 30px; text-align: right;">Payment Terms: Net {{ $invoice->netdays }}</span>
                        </div>
                    </div>
                    <div class="row invoice-data" style="position: relative; margin-bottom: 320px;">
                        <div class="col-xs-5 invoice-person" style="position: absolute; left: 0; padding: 0 15px; ">
                            <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">Bill To: </span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->companyname }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">@if($invoice->client->block){{ $invoice->client->block }} @endif {{ $invoice->client->street or 'No Street' }}</span>
                            @if($invoice->client->unitnumber)<span style="font-size: 18px; line-height: 26px; display: block;">#{{ $invoice->client->unitnumber }}</span>@endif
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->country or 'No Country' }} {{ $invoice->client->postalcode or 'No Postal Code' }}</span>
                        </div>
                        <div class="col-xs-2 invoice-payment-direction" style="position: absolute; padding-top: 10px; left: 0; right:0; text-align: center;">
                            <img src="{{ asset('/assets/img/lefttoright.png') }}" width="80" height="80" />
                        </div>
                        <div class="col-xs-5 invoice-person" style="position: absolute; right: 0; padding: 0 15px; text-align: left;">
                            <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">{{ $invoice->company->name or 'No Company Name' }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">{{ $invoice->company->crn or 'No Company Registration Number' }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->company->owner->full_name or 'No Company Owner Name' }}</span>
                            @if($invoice->company->address)
                                <span style="font-size: 18px; line-height: 26px; display: block;">@if($invoice->company->address->block){{ $invoice->company->address->block }} @endif {{ $invoice->company->address->street or 'No Street' }}</span>
                                @if($invoice->company->address->unitnumber)<span style="font-size: 18px; line-height: 26px; display: block;">#{{ $invoice->company->address->unitnumber }}</span>@endif
                                <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->company->address->postalcode or 'No Postal Code' }}</span>
                            @else
                                <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->company->owner->email or 'No Company Owner Email' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"
                             style="position: relative; padding: 0 15px;">
                            <table class="invoice-details" style="border-collapse: collapse; border-spacing: 0; background-color: transparent; width: 100%; font-size: 16px;">
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
                                    @foreach($invoice->items as $key => $item)
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
                                            ${{ $invoice->calculatetotal() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 0;"></td>
                                        <td class="summary" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; color: #aaaaaa;">
                                            Tax (0%)
                                        </td>
                                        <td class="amount" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; text-align: right;">
                                            $0,00
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 20px 0;"></td>
                                        <td class="summary total" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; color: #8c8c8c; font-weight: 700;">
                                            Total
                                        </td>
                                        <td class="amount total-value" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; text-align: right; font-size: 22px; color: #4da6a6;">
                                            ${{ $invoice->calculatetotal() }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 invoice-message" style="position: relative; padding: 0 15px; font-size: 16px; margin-bottom: 62px;">
                            <span class="title" style="font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 12px;">Terms & Conditions</span>
                            {!! $invoice->company->settings->invoice_conditions !!}
                        </div>
                    </div>
                    <div class="row invoice-company-info" style="margin-bottom: 70px;">
                        <div class="logo" style="position: relative; display: block; width: 100%;  text-align: center;">
                            <img src="{{ $invoice->company->smlogo }}" alt="Logo-symbol" width="100" height="100" style="border: 0; vertical-align: middle;">
                        </div>
                        <div style="margin-top: 20px;">
                            <div class="row">
                                <div class="col s6 m4 summary" style="display: inline-block; padding: 0 15px; line-height: 16px; text-align: center;">
                                    <span class="title" style="color: #8c8c8c; font-size: 14px; line-height: 21px; font-weight: 700;">{{ $invoice->company->name or 'No Company Name' }}</span>
                                    <p style="font-size: inherit; margin: 0 0 15px; line-height: 16px;"></p>
                                </div>
                                <div class="col s6 m4 phone" style="display: inline-block; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                                    <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                                        <li> {{ $invoice->company->phone or 'No Phone Number' }}</li>
                                    </ul>
                                </div>
                                <div class="col s6 m4 email" style="display: inline-block; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                                    <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                                        <li>{{ $invoice->company->email or 'No Email' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row invoice-footer" style="text-align: center;">
                        <div class="col s12" style="position: relative; padding: 0 15px;">
                            <a class="btn btn-lg btn-space btn-default" style="-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); border: 1px solid transparent; color: #404040; background-color: #fff; border-color: #dedede; padding: 0 12px; line-height: 38px; border-radius: 3px; font-weight: 700; margin-right: 5px; margin-bottom: 5px; min-width: 96px; font-size: 14px;" href="{{ route('invoice.download', [ 'invoice' => $invoice->id] ) }}">
                                Save PDF
                            </a>
                            <a class="btn btn-lg btn-space btn-default" style="-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); border: 1px solid transparent; color: #404040; background-color: #fff; border-color: #dedede; padding: 0 12px; line-height: 38px; border-radius: 3px; font-weight: 700; margin-right: 5px; margin-bottom: 5px; min-width: 96px; font-size: 14px;" href="{{ route('invoice.printview', [ 'invoice' => $invoice->id] ) }}">
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
            <p>Delete Invoice?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-invoice-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal invoice-confirm-delete-btn" type="submit">Delete</button>
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
                <a href="#" data-id="{{ $invoice->id }}" class="invoice-regenerate-btn btn btn-link waves-effect waves-dark">
                    Regenerate Link
                </a>
            </div>
            <input id="shared-link" type="text" value="{{ route('invoice.token', [ 'token' => $invoice->share_token] ) }}">
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

            $('#top-action-container').on('click', '.invoice-share-btn', function (event) {
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

            $('#shared-details').on('click', '.invoice-regenerate-btn', function (event) {
                event.preventDefault();
                getSharedLink();
            });


            $('#invoice-action-container').on('click', '.invoice-delete-btn', function (event) {
                event.preventDefault();
                let invoiceid = $(this).attr('data-id');
                $('#delete-invoice-form').attr('action', '/invoice/' + invoiceid + '/destroy');
                $('#delete-confirmation').modal('open');
            });

            $('#change-history-container').slick({
                // normal options...
                infinite: false,
                arrows: false,
                dots: true,
                slidesToShow: 3,
                slidesToScroll: 3,
                adaptiveHeight: false,
                responsive: [
                    {
                        breakpoint: 1900,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 1600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 1200,
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
                    url: "{{ route('invoice.share', [ 'invoice' => $invoice->id]) }}",
                })
                .done(function(data) {
                    $('#shared-link').val("{{ route('invoice.token') }}?token=" + data);
                    $('#shared-details').modal('open');
                });
            }
        });
    </script>
@stop