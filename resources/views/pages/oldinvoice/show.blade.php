@extends("layouts/default")

@section("head")
    <title>{{ config('app.name') }}</title>
    <style>

    </style>
@stop

@section("content")
    <div class="mini-container">
        <div class="row">
            <div class="col s6 mtop30">
                <a class="btn btn-lg btn-default" href="{{ route('invoice.show', [ 'invoice' => $invoice->invoice_id] ) }}">
                    Back
                </a>
            </div>
            <div class="col s6 mtop30 right">
                <a class="btn btn-lg btn-default" href="{{ route('invoice.old.download', [ 'invoice' => $invoice->id] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-lg btn-default" href="{{ route('invoice.old.printview', [ 'invoice' => $invoice->id] ) }}">
                    Print
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <h3>Old Invoice</h3>
                <div class="invoice" style="background-color: #ffffff; padding: 50px 50px 20px; color: #8c8c8c;">
                    <div class="row invoice-header" style="position: relative; margin-bottom: 50px;">
                        <div class="col-xs-7" style="position: relative; left: 0; padding: 0 15px; width: 50%; float: left;">
                            <div class="invoice-logo" style="height: 110px; min-width: 210px; background-image: url('{{ \App\Library\Poowf\Unicorn::getStorageFile($invoice->company->logo, [210, 110]) }}'); background-repeat: no-repeat; background-position: 0; background-size: contain;"></div>
                        </div>
                        <div class="col-xs-5 invoice-order" style="position: relative; padding: 0 15px; text-align: right; width: 50%; float: left;">
                            <span class="invoice-id" style="display: block; font-size: 30px; line-height: 30px; margin-bottom: 10px;">Invoice #{{ $invoice->nice_invoice_id }}</span>
                            <span class="invoice-date" style="display: block; font-size: 18px; line-height: 30px;">Invoice Date: {{ $invoice->date }}</span>
                            <span class="invoice-duedate" style="display: block; font-size: 18px; line-height: 30px;">Payment Due: {{ $invoice->duedate }}</span>
                            <span class="invoice-netdays" style="display: block; font-size: 18px; line-height: 30px;">Payment Terms: Net {{ $invoice->netdays }}</span>
                        </div>
                    </div>
                    <div class="row invoice-data" style="position: relative; margin-bottom: 50px;">
                        <div class="col-xs-5 invoice-person" style="position: relative; left: 0; padding: 0 15px; width:45%; float: left; text-align: left;">
                            <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">Bill To: </span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->companyname }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">@if($invoice->client->block){{ $invoice->client->block }} @endif {{ $invoice->client->street or 'No Street' }}</span>
                            @if($invoice->client->unitnumber)<span style="font-size: 18px; line-height: 26px; display: block;">#{{ $invoice->client->unitnumber }}</span>@endif
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->country or 'No Country' }} {{ $invoice->client->postalcode or 'No Postal Code' }}</span>
                        </div>
                        <div class="col-xs-2 invoice-payment-direction" style="position: relative; padding-top: 10px; width: 10%; float: left; text-align: center;">
                            <img src="{{ asset('/assets/img/lefttoright.png') }}" width="80" height="80" />
                        </div>
                        <div class="col-xs-5 invoice-person" style="position: relative; padding: 0 15px; width: 45%; float: left;">
                            <div class="" style="float: right; text-align: left;">
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
                        <div class="col-md-12 invoice-payment-method" style="position: relative; padding: 0 15px; margin-bottom: 75px;">
                            <span class="title" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">Payment Method</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">Credit card</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">Credit card type: mastercard</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">Number verification: 4256981387</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 invoice-message" style="position: relative; padding: 0 15px; font-size: 16px; margin-bottom: 62px;">
                            <span class="title" style="font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 12px;">Thank you for contacting us</span>
                            <p style="font-size: inherit; margin: 0 0 15px; line-height: 26px;">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas quis massa nisl. Sed fringilla turpis id mi ultrices, et faucibus ipsum aliquam. Sed ut eros placerat, facilisis est eu, congue felis.
                            </p>
                        </div>
                    </div>
                    <div class="row invoice-company-info" style="margin-bottom: 70px;">
                        <div class="col-sm-6 col-md-2 logo" style="position: relative; display: block; width: 100%;  text-align: center;">
                            <img src="{{ \App\Library\Poowf\Unicorn::getStorageFile($invoice->company->smlogo, [100,100]) }}" alt="Logo-symbol" width="100" height="100" style="border: 0; vertical-align: middle;">
                        </div>
                        <div style="margin-top: 20px;">
                            <div class="col-sm-6 col-md-4 summary" style="display: inline-block; width: 29.5%; padding: 0 15px; line-height: 16px; text-align: center;">
                                <span class="title" style="color: #8c8c8c; font-size: 14px; line-height: 21px; font-weight: 700;">{{ $invoice->company->name or 'No Company Name' }}</span>
                                <p style="font-size: inherit; margin: 0 0 15px; line-height: 16px;"></p>
                            </div>
                            <div class="col-sm-6 col-md-3 phone" style="display: inline-block; width: 29.5%; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                                <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                                    <li> {{ $invoice->company->phone or 'No Phone Number' }}</li>
                                </ul>
                            </div>
                            <div class="col-sm-6 col-md-3 email" style="display: inline-block; width: 29.5%; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                                <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                                    <li>{{ $invoice->company->email or 'No Email' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row invoice-footer" style="text-align: center;">
                        <div class="col-md-12" style="position: relative; padding: 0 15px;">
                            <a class="btn btn-lg btn-space btn-default" style="-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); border: 1px solid transparent; color: #404040; background-color: #fff; border-color: #dedede; padding: 0 12px; line-height: 38px; border-radius: 3px; font-weight: 700; margin-right: 5px; margin-bottom: 5px; min-width: 96px; font-size: 14px;" href="{{ route('invoice.old.download', [ 'invoice' => $invoice->id] ) }}">
                                Save PDF
                            </a>
                            <a class="btn btn-lg btn-space btn-default" style="-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05); border: 1px solid transparent; color: #404040; background-color: #fff; border-color: #dedede; padding: 0 12px; line-height: 38px; border-radius: 3px; font-weight: 700; margin-right: 5px; margin-bottom: 5px; min-width: 96px; font-size: 14px;" href="{{ route('invoice.old.printview', [ 'invoice' => $invoice->id] ) }}">
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
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
        });
    </script>
@stop