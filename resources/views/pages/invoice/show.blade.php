@extends("layouts/default")

@section("head")
    <title>Invoice Plz</title>
    <style>

    </style>
@stop

@section("content")
    <div class="mini-container">
        <div class="row">
            <div class="col s6">
            </div>
            <div class="col s6 mtop30 right">
                <a class="btn btn-lg btn-default" href="{{ route('payment.create', [ 'invoice' => $invoice->id] ) }}">
                    Log Payment
                </a>
                <a class="btn btn-lg btn-default" href="{{ route('invoice.download', [ 'invoice' => $invoice->id] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-lg btn-default" href="{{ route('invoice.printview', [ 'invoice' => $invoice->id] ) }}">
                    Print
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col s12 l6">
                <h3>Details</h3>
                <div class="card-panel">
                    <dt>Company Name</dt>
                    <dd>{{ $client->companyname }}</dd>
                    <dt>Company Address</dt>
                    <dd>{{ $client->address }}</dd>
                    <dt>Company Nickname</dt>
                    <dd>{{ $client->nickname or '' }}</dd>
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
                        @if ($invoice->status == 0)
                            <span class="alt-badge error">{{ $invoice->statustext() }}</span>
                        @elseif ($invoice->status == 1)
                            <span class="alt-badge success">{{ $invoice->statustext() }}</span>
                        @endif
                    </dd>
                    </dl>
                </div>
            </div>
            <div class="col s12 l6">
                <h3>Change History</h3>
                <div class="card-panel flex">
                    <table id="history-container" class="responsive-table striped">
                        <thead>
                        <tr>
                            <th>Date of Invoice</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($histories as $key => $history)
                            <tr>
                                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $history->created_at)->format('j F, Y, h:i:s a') }}</td>
                                <td>
                                    <a href="{{ route('invoice.old.show', [ 'oldinvoice' => $history->oldinvoice_id ] ) }}"><i class="material-icons">open_in_new</i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col s12 l8 offset-l2">
                <h3>Invoice</h3>
                <div class="invoice" style="background-color: #ffffff; padding: 50px 50px 20px; color: #8c8c8c;">
                    <div class="row invoice-header" style="position: relative; margin-bottom: 160px;">
                        <div class="col-xs-7" style="position: absolute; left: 0; padding: 0 15px;">
                            <div class="invoice-logo" style="height: 110px; min-width: 210px; background-image: url('{{ $invoice->company->logo }}'); background-repeat: no-repeat; background-position: 0;"></div>
                        </div>
                        <div class="col-xs-5 invoice-order" style="position: absolute; right: 0; padding: 0 15px; text-align: left;">
                            <span class="invoice-id" style="display: block; font-size: 30px; line-height: 30px; margin-bottom: 10px;">Invoice #{{ $invoice->invoiceid }}</span>
                            <span class="incoice-date" style="display: block; font-size: 18px; line-height: 30px;">Invoice Date: {{ $invoice->date }}</span>
                            <span class="incoice-duedate" style="display: block; font-size: 18px; line-height: 30px;">Payment Due: {{ $invoice->duedate }}</span>
                            <span class="incoice-netdays" style="display: block; font-size: 18px; line-height: 30px;">Payment Terms: Net {{ $invoice->netdays }}</span>
                        </div>
                    </div>
                    <div class="row invoice-data" style="position: relative; margin-bottom: 320px;">
                        <div class="col-xs-5 invoice-person" style="position: absolute; left: 0; padding: 0 15px; ">
                            <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">Bill To: </span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->companyname }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->contactname }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->address }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->contactphone }}</span>
                        </div>
                        <div class="col-xs-2 invoice-payment-direction" style="position: absolute; padding-top: 10px; left: 0; right:0; text-align: center;">
                            <img src="{{ asset('/assets/img/lefttoright.png') }}" width="80" height="80" />
                        </div>
                        <div class="col-xs-5 invoice-person" style="position: absolute; right: 0; padding: 0 15px; text-align: left;">
                            <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">{{ $invoice->company->name or 'No Company Name' }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->company->owner->name or 'No Company Owner Name' }}</span>
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->company->owner->email or 'No Company Owner Email' }}</span>
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
                                            {{ $item->description }}
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
                            <img src="{{ $invoice->company->smlogo }}" alt="Logo-symbol" width="100" height="100" style="border: 0; vertical-align: middle;">
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
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
        });
    </script>
@stop