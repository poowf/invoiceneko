@section("receipt")
    <style>
        .receipt {
            font-size: 1.1em;
            line-height: 1.5em;
        }

        .receipt table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .receipt table tr {
            border: 0;
        }

        .receipt table td {
            padding: 5px;
            vertical-align: top;
        }

        .receipt table tr.company-information td:nth-child(2) {
            text-align: center;
        }

        .receipt table tr.information td:nth-child(2) {
            text-align: center;
        }

        .receipt table th {
            color: #8c8c8c;
            padding: 10px 5px;
        }

        .receipt table th:nth-child(3) {
            text-align: right;
        }

        .receipt table tr td:nth-child(3) {
            text-align: right;
        }

        .receipt table tr.top table td {
            padding-bottom: 20px;
        }

        .receipt table tr table td span {
            color: #8c8c8c;
            display: block;
        }

        .receipt table tr.information table table {
            float: left;
        }

        .receipt table tr.information table table tbody {
            float: right;
        }

        .receipt table tr.information table table td {
            padding: 0;
        }

        .receipt table tr.information table td {
            padding-bottom: 20px;
        }

        .receipt table tr.company-logo td {
            text-align: center;
        }

        .receipt table tr.details td {
            padding: 20px 5px;
        }

        .receipt .bottom-line {
            border-bottom: 1px solid #f0f0f0;
        }

        .receipt .left-line {
            border-left: 2px solid #e0e0e0;
        }

        .receipt .summary, .receipt .amount, .receipt .quantity {
            color: #8c8c8c;
        }

        .receipt .total-value {
            color: #4da6a6;
        }

        .receipt-bold {
            color: #8c8c8c;
            font-weight: bold;
        }

        .receipt-text-larger {
            font-size: 1.3em;
            padding-bottom: 10px;
        }

        @media only screen and (max-width: 600px) {
            .receipt .left-line {
                border: 0;
            }

            .receipt {
                padding: 20px;
            }

            .receipt table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .receipt table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .receipt table tr.information table table {
                float: none;
            }

            .receipt table tr.information table table tbody {
                float: none;
            }

            .receipt table tr.company-information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
    </style>
    <div class="col s12 l10 push-l1">
        <h3>Receipt</h3>
        <div class="receipt card-panel">
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                    <td>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <img src="{{ \App\Library\Poowf\Unicorn::getStorageFile($invoice->company->logo, [210,110]) }}" width="210" height="110">
                                </td>
                                <td></td>
                                <td>
                                    <span class="receipt-id receipt-bold receipt-text-larger">Receipt #{{ $receipt->nice_receipt_id }}</span>
                                    <span class="receipt-date">Receipt Date: {{ $receipt->created_at->format('d F, Y') }}</span>
                                    <span class="receipt-netdays">Receipt for Invoice #{{ $invoice->nice_invoice_id }}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="information">
                    <td>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="250">
                                    <span class="name receipt-bold receipt-text-larger">Prepared For: </span>
                                    <span>{{ $invoice->getClient()->companyname }}</span>
                                    <span>@if($invoice->getClient()->block){{ $invoice->getClient()->block }} @endif {{ $invoice->getClient()->street ?? 'No Street' }}</span>
                                    @if($invoice->getClient()->unitnumber)<span>#{{ $invoice->getClient()->unitnumber }}</span>@endif
                                    <span>{{ $invoice->getClient()->country_code ?? 'No Country' }} {{ $invoice->getClient()->postalcode ?? 'No Postal Code' }}</span>
                                </td>
                                <td width="250">
                                    <img src="{{ asset('/assets/img/lefttoright.png') }}" width="80" height="80" />
                                </td>
                                <td width="250">
                                    <table cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td><span class="name receipt-bold receipt-text-larger">{{ $invoice->company->name ?? 'No Company Name' }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="receipt-bold">{{ $invoice->company->crn ?? 'No Company Registration Number' }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>{{ $invoice->company->owner->full_name ?? 'No Company Owner Name' }}</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if($invoice->company->address)
                                                    <span>@if($invoice->company->address->block){{ $invoice->company->address->block }} @endif {{ $invoice->company->address->street ?? 'No Street' }}</span>
                                                    @if($invoice->company->address->unitnumber)<span>#{{ $invoice->company->address->unitnumber }}</span>@endif
                                                    <span>{{ $invoice->company->address->postalcode ?? 'No Postal Code' }}</span>
                                                @else
                                                    <span>{{ $invoice->company->owner->email ?? 'No Company Owner Email' }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="details">
                    <td>
                        <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0; background-color: transparent;">
                            <tbody>
                                <tr class="bottom-line">
                                    <th width="300">
                                        Description
                                    </th>
                                    <th width="160">
                                        Quantity
                                    </th>
                                    <th width="200">
                                        Amount
                                    </th>
                                </tr>
                                @foreach($invoice->items as $key => $item)
                                    <tr class="bottom-line">
                                        <td class="description" width="300">
                                            <span class="receipt-bold">{{ $item->name }}</span>
                                            <p>{!! $item->description !!}</p>
                                        </td>
                                        <td class="quantity" width="160">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="amount" width="200">
                                            ${{ $item->moneyformatprice() }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td width="300"></td>
                                    <td class="summary bottom-line" width="160">
                                        Subtotal
                                    </td>
                                    <td class="amount bottom-line" width="200">
                                        ${{ $invoice->calculatesubtotal() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="300"></td>
                                    <td class="summary bottom-line" width="160">
                                        Tax ({{ $invoice->company->settings->tax ?? 0 }}%)
                                    </td>
                                    <td class="amount bottom-line" width="200">
                                        ${{ $invoice->calculatetax() }}
                                    </td>
                                </tr>
                                <tr>
                                    <td width="300"></td>
                                    <td class="summary bottom-line total receipt-bold" width="160">
                                        Total
                                    </td>
                                    <td class="amount bottom-line receipt-text-larger total-value" width="200">
                                        ${{ $invoice->calculatetotal() }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr class="company-logo">
                    <td>
                        <img src="{{ \App\Library\Poowf\Unicorn::getStorageFile($invoice->company->smlogo, [100,100]) }}" alt="{{ $invoice->company->name ?? 'No Company Name' }}" width="100" height="100">
                    </td>
                </tr>

                <tr class="company-information">
                    <td>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="250"><span>{{ $invoice->company->name ?? 'No Company Name' }}</span></td>
                                <td width="250" class="left-line"><span>{{ $invoice->company->phone ?? 'No Phone Number' }}</span></td>
                                <td width="250" class="left-line"><span>{{ $invoice->company->email ?? 'No Email' }}</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row receipt-footer">
            <div class="col s12 center">
                <a class="btn btn-lg btn-space btn-default" href="{{ route('receipt.download', [ 'receipt' => $receipt, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-lg btn-space btn-default" href="{{ route('receipt.printview', [ 'receipt' => $receipt, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
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
@show