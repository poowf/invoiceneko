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
        <div class="quote" style="background-color: #ffffff; padding: 50px 50px 20px; color: #8c8c8c;">
            <div class="row quote-header" style="position: relative; margin-bottom: 160px;">
                <div style="position: absolute; left: 0; padding: 0 15px;">
                    <div class="quote-logo" style="height: 110px; min-width: 210px; background-image: url('{{ asset($quote->company->logo) }}'); background-repeat: no-repeat; background-position: 0;"></div>
                </div>
                <div class="quote-order" style="position: absolute; right: 0; padding: 0 15px; text-align: right;">
                    <span class="quote-id" style="display: block; font-size: 30px; line-height: 30px; margin-bottom: 10px;">Quote #{{ $quote->nice_quote_id }}</span>
                    <span class="quote-date" style="display: block; font-size: 18px; line-height: 30px;">Quote Date: {{ $quote->date }}</span>
                    <span class="quote-duedate" style="display: block; font-size: 18px; line-height: 30px;">Quote Expires: {{ $quote->duedate }}</span>
                </div>
            </div>
            <div class="row quote-data" style="position: relative; margin-bottom: 320px;">
                <div class="quote-person" style="position: absolute; left: 0; padding: 0 15px; ">
                    <span class="name" style="font-size: 18px; line-height: 26px; display: block; font-weight: 700;">Prepared For: </span>
                    <span style="font-size: 18px; line-height: 26px; display: block;">{{ $quote->client->companyname }}</span>
                    <span style="font-size: 18px; line-height: 26px; display: block;">@if($quote->client->block){{ $quote->client->block }} @endif {{ $quote->client->street ?? 'No Street' }}</span>
                    @if($quote->client->unitnumber)<span style="font-size: 18px; line-height: 26px; display: block;">#{{ $quote->client->unitnumber }}</span>@endif
                    <span style="font-size: 18px; line-height: 26px; display: block;">{{ $quote->client->country ?? 'No Country' }} {{ $quote->client->postalcode ?? 'No Postal Code' }}</span>
                </div>
                <div class="quote-payment-direction" style="position: absolute; padding-top: 10px; left: 0; right:0; text-align: center;">
                    <img src="{{ asset('/assets/img/lefttoright.png') }}" width="80" height="80" />
                </div>
                <div class="quote-person" style="position: absolute; right: 0; padding: 0 15px; text-align: left;">
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
                <div style="position: relative; padding: 0 15px; padding-top: 20px;">
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
                                    ${{ $quote->calculatetotal() }}
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
                                    ${{ $quote->calculatetotal() }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row quote-company-info" style="margin-bottom: 70px;">
                <div class="logo" style="position: relative; display: block; width: 100%;  text-align: center;">
                    <img src="{{ asset($quote->company->smlogo) }}" alt="Company Logo" width="100" height="100" style="border: 0; vertical-align: middle;">
                </div>
                <div style="margin-top: 20px;">
                    <div class="summary" style="display: inline-block; width: 29.5%; padding: 0 15px; line-height: 16px; text-align: center;">
                        <span class="title" style="color: #8c8c8c; font-size: 14px; line-height: 21px; font-weight: 700;">{{ $quote->company->name ?? 'No Company Name' }}</span>
                        <p style="font-size: inherit; margin: 0 0 15px; line-height: 16px;"></p>
                    </div>
                    <div class="phone" style="display: inline-block; width: 29.5%; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                        <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                            <li> {{ $quote->company->phone ?? 'No Phone Number' }}</li>
                        </ul>
                    </div>
                    <div class="email" style="display: inline-block; width: 29.5%; padding: 0 15px; border-left: 2px solid #e0e0e0; text-align: center;">
                        <ul class="list-unstyled" style="margin-top: 0; margin-bottom: 9px; line-height: 20px; padding-left: 0; list-style: none;">
                            <li>{{ $quote->company->email ?? 'No Email' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="page row">
                <div class="invoice-message" style="position: relative; padding: 0 15px; font-size: 16px; padding-top: 62px; margin-bottom: 62px;">
                    <span class="title" style="font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 12px;">Terms & Conditions</span>
                    {!! $quote->company->settings->quote_conditions !!}
                </div>
            </div>
        </div>
    </body>
</html>