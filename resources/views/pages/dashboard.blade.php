@extends("layouts.default", ['page_title' => 'Dashboard'])

@section("head")
    <link href="{{ asset(mix('/assets/css/chartist.css')) }}" rel="stylesheet" type="text/css">
    <style>
    </style>
@stop

@section("content")
    <div class="wide-container">
        <div class="row mtop30">
            <div class="col s12">
                <h2>Dashboard</h2>
            </div>
            <div class="col s12 m4">
                <h3>Welcome</h3>
                <div class="card-panel">
                    <h5>Hello {{ $user->full_name ?? '' }}</h5>
                    <h6>Today is {{ \Carbon\Carbon::now()->format('j F, Y') }}</h6>
                    <h6 class="mtop20">Your company has created a total of:</h6>
                    <dl>
                        <dt>Invoices:</dt>
                        <dd>{{ $total['invoices'] }}</dd>
                        <dt>Quotes:</dt>
                        <dd>{{ $total['quotes'] }}</dd>
                        <dt>Payments:</dt>
                        <dd>{{ $total['payments'] }}</dd>
                    </dl>
                </div>
            </div>
            <div class="col s12 m8">
                <h3>Activity in the past week</h3>
                <div class="card-panel">
                    <h5 class="center">Items Created</h5>
                    <div id="summary-chart"></div>
                </div>
            </div>
            <div class="col s12">
                <h3>Overdue Invoices</h3>
                <div class="card-panel flex">
                    <table class="responsive-table">
                        <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Client Company</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                            @if($overdueinvoices)
                                @foreach($overdueinvoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->nice_invoice_id }}</td>
                                        <td>{{ $invoice->getClient()->companyname }}</td>
                                        <td>${{ $invoice->calculatetotal() }}</td>
                                        <td>{{ $invoice->duedate->format('d F, Y') }}</td>
                                        <td>
                                            <a href="{{ route('invoice.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ])  }}" class="btn waves-effect waves-light">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            new Chartist.Line('#summary-chart', {
                labels: [@foreach($activity['dates'] as $date) {!! "'" . $date . "'," !!} @endforeach],
                series: [
                    [@foreach($activity['invoices'] as $invoice) {!! "'" . $invoice . "'," !!} @endforeach],
                    [@foreach($activity['quotes'] as $quote) {!! "'" . $quote . "'," !!} @endforeach],
                    [@foreach($activity['payments'] as $payment) {!! "'" . $payment . "'," !!} @endforeach],
                ]
            }, {
                fullWidth: true,
                chartPadding: {
                    right: 40
                },
                plugins: [
                    Chartist.plugins.legend({
                        legendNames: ['Invoices', 'Quotes', 'Payments'],
                    })
                ]
            });
        });
    </script>
@stop