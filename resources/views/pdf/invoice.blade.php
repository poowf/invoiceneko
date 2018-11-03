<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="font-family: sans-serif; overflow-x: hidden;">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <style>
            div.page
            {
                page-break-before: always;
                page-break-inside: avoid;
            }
        </style>
    </head>
    <body style="margin: 0; font-family: 'Roboto', Arial, sans-serif;font-size: 13px;">
        <div class="invoice" style="background-color: #ffffff; padding: 50px 50px 20px; color: #8c8c8c;">
            <div class="row invoice-header" style="position: relative; margin-bottom: 180px;">
                <div style="position: relative; left: 0; padding: 0 15px; width: 45%; float: left;">
                    <div class="invoice-logo" style="height: 110px; min-width: 210px; background-image: url('{{ \App\Library\Poowf\Unicorn::getStorageFile($invoice->company->logo, [210,110]) }}'); background-repeat: no-repeat; background-position: 0; background-size: contain;"></div>
                </div>
                <div class="invoice-order" style="position: relative; padding: 0 15px; width: 48%; float: left; text-align: right;">
                    <span class="invoice-id" style="display: block; font-size: 30px; line-height: 30px; margin-bottom: 10px;">Invoice #{{ $invoice->nice_invoice_id }}</span>
                    <span class="invoice-date" style="display: block; font-size: 18px; line-height: 30px;">Invoice Date: {{ $invoice->date }}</span>
                    <span class="invoice-duedate" style="display: block; font-size: 18px; line-height: 30px;">Payment Due: {{ $invoice->duedate }}</span>
                    <span class="invoice-netdays" style="display: block; font-size: 18px; line-height: 30px;">Payment Terms: Net {{ $invoice->netdays }}</span>
                </div>
            </div>
            <div class="row invoice-data" style="position: relative; margin-bottom: 320px;">
                <div class="invoice-person" style="position: relative; left: 0; padding: 0 15px; width:43%; float: left; text-align: left;">
                    <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">Bill To: </span>
                    <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->companyname }}</span>
                    <span style="font-size: 18px; line-height: 26px; display: block;">@if($invoice->client->block){{ $invoice->client->block }} @endif {{ $invoice->client->street ?? 'No Street' }}</span>
                    @if($invoice->client->unitnumber)<span style="font-size: 18px; line-height: 26px; display: block;">#{{ $invoice->client->unitnumber }}</span>@endif
                    <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->client->country ?? 'No Country' }} {{ $invoice->client->postalcode ?? 'No Postal Code' }}</span>
                </div>
                <div class="invoice-payment-direction" style="position: relative; padding-top: 10px; width: 7%; float: left; text-align: center;">
                    <img src="{{ asset('/assets/img/lefttoright.png') }}" width="80" height="80" />
                </div>
                <div class="invoice-person" style="position: relative; padding: 0 15px; width: 43%; float: left;">
                    <div class="invoice-person-container" style="float: right; text-align: left;">
                        <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">{{ $invoice->company->name ?? 'No Company Name' }}</span>
                        <span style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">{{ $invoice->company->crn ?? 'No Company Registration Number' }}</span>
                        <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->company->owner->full_name ?? 'No Company Owner Name' }}</span>
                        @if($invoice->company->address)
                            <span style="font-size: 18px; line-height: 26px; display: block;">@if($invoice->company->address->block){{ $invoice->company->address->block }} @endif {{ $invoice->company->address->street ?? 'No Street' }}</span>
                            @if($invoice->company->address->unitnumber)<span style="font-size: 18px; line-height: 26px; display: block;">#{{ $invoice->company->address->unitnumber }}</span>@endif
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->company->address->postalcode ?? 'No Postal Code' }}</span>
                        @else
                            <span style="font-size: 18px; line-height: 26px; display: block;">{{ $invoice->company->owner->email ?? 'No Company Owner Email' }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="position: relative; padding: 0 15px; padding-top: 20px;">
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
                                    ${{ $invoice->calculatesubtotal() }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 0;"></td>
                                <td class="summary" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; color: #aaaaaa;">
                                    Tax ({{ $invoice->company->settings->tax ?? 0 }}%)
                                </td>
                                <td class="amount" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; text-align: right;">
                                    ${{ $invoice->calculatetax() }}
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
            <div class="row invoice-company-info" style="margin-top: 50px; margin-bottom: 70px;">
                <div class="logo" style="position: relative; display: block; width: 100%;  text-align: center;">
                    <img src="{{ \App\Library\Poowf\Unicorn::getStorageFile($invoice->company->smlogo, [100,100]) }}" alt="Company Logo" width="100" height="100" style="border: 0; vertical-align: middle;">
                </div>
                <div style="margin-top: 20px;">
                    <div class="summary" style="display: inline-block; width: 29%; padding: 0 15px; line-height: 16px; text-align: center;">
                        <span class="title" style="color: #8c8c8c; font-size: 14px; line-height: 21px; font-weight: 700;">{{ $invoice->company->name ?? 'No Company Name' }}</span>
                        <p style="font-size: inherit; margin: 0 0 15px; line-height: 16px;"></p>
                    </div>
                    <div class="phone" style="display: inline-block; width: 29%; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                        <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                            <li> {{ $invoice->company->phone ?? 'No Phone Number' }}</li>
                        </ul>
                    </div>
                    <div class="email" style="display: inline-block; width: 29%; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                        <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                            <li>{{ $invoice->company->email ?? 'No Email' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="page row">
                <div class="invoice-message" style="position: relative; padding: 0 15px; font-size: 16px; padding-top: 62px; margin-bottom: 62px;">
                    <span class="title" style="font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 12px;">Terms & Conditions</span>
                    {!! $invoice->company->settings->invoice_conditions !!}
                </div>
            </div>
        </div>
    </body>
</html>