@extends("layouts.default", ['page_title' => 'Quotes'])

@section("head")
    <style>
        .card-panel.tab-panel {
            margin-top: 0;
        }
        .tab {
            background-color: #299a9a;
        }
        .tabs .tab a {
            color: #cbdede;
        }
        .tabs .tab a:hover, .tabs .tab a.active {
            color: #fff;
        }
        .tabs .indicator {
            height: 5px;
            background-color: #FFD264;
        }
        #receipt-container {
            margin: 0px;
        }
    </style>
@stop

@section("content")
    <div class="wide-container">
        <div class="row">
            <div class="col s6">
                <h3>Receipts</h3>
            </div>

            <div class="col s6 right mtop30">
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel search-panel">
                    <input id="search-input" class="card-input" name="search-input" type="search" placeholder="Search">
                </div>

                <div id="receipt-container" class="row">
                    <div class="card-panel flex">
                        <table id="receipts-table" class="responsive-table striped">
                            <thead>
                                <tr>
                                    <th>Receipt ID</th>
                                    <th>Invoice ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($receipts as $key => $receipt)
                                    <tr class="single-receipt-row">
                                        <td>{{ $receipt->nice_receipt_id }}</td>
                                        <td>{{ $receipt->invoice->nice_invoice_id  }}</td>
                                        <td>
                                            @can('view', $receipt)
                                                <a href="{{ route('receipt.show', [ 'receipt' => $receipt, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="tooltipped" data-position="top" data-tooltip="View Receipt"><i class="material-icons">remove_red_eye</i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
            Unicorn.initPageSearch('#search-input', '#receipt-container .single-receipt-row');
        });
    </script>
@stop