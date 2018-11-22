@extends("layouts.default", ['page_title' => 'Invoice | View'])

@section("head")
    <style>
        :root .card.single-history {
            overflow: hidden;
            margin: 0px 20px;
            padding: 35px;
            text-align: center;
        }

        .single-history-wrapper {
        }

        :root .slick-prev:before, :root .slick-next:before{
            content: initial;
            color: #000000;
        }

        :root .slick-prev, :root .slick-next {
            color: #000000;
            z-index: 1;
        }

        :root .slick-prev:hover, :root .slick-next:hover, :root .slick-prev:focus, :root .slick-next:focus {
            color: #000000;
        }

        :root .slick-prev {
            left: -20px;
        }

        :root .slick-next {
            right: -5px;
        }
    </style>
    <link href="{{ mix('/assets/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('/assets/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
@stop

@section("content")
    <div class="mini-container">
        <div id="top-action-container" class="row desktop-only">
            <div class="col s12 mtop30 right">
                <form method="post" action="{{ route('invoice.send', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn btn-link waves-effect waves-dark null-btn" type="submit">Send Notification</button>
                </form>
                @can('create', \App\Models\Payment::class)
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('payment.create', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                    Log Payment
                </a>
                @endcan
                <a href="#" data-id="{{ $invoice->id }}" class="invoice-share-btn btn btn-link waves-effect waves-dark">
                    Share
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('invoice.download', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                    Save PDF
                </a>
                <a class="btn btn-link waves-effect waves-dark" href="{{ route('invoice.printview', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                    Print
                </a>
            </div>
        </div>
        @can('update', $invoice)
        <div id="invoice-action-container" class="row mbtm0 desktop-only">
            <div class="col s12 right">
                @can('update', $invoice)
                <form method="post" action="{{ route('invoice.convert', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn btn-link waves-effect waves-dark null-btn" type="submit">Convert back to Quote</button>
                </form>
                @endcan
                @can('update', $invoice)
                <form method="post" action="{{ route('invoice.duplicate', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form">
                    {{ csrf_field() }}
                    <button class="btn blue darken-3 waves-effect waves-dark null-btn" type="submit">Clone</button>
                </form>
                @endcan
                @can('update', $invoice)
                <form method="post" action="{{ route('invoice.writeoff', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <button class="btn deep-orange darken-2 waves-effect waves-dark null-btn" type="submit">Write Off</button>
                </form>
                @endcan
                @can('update', $invoice)
                <form method="post" action="{{ route('invoice.archive', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form">
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <button class="btn amber darken-2 waves-effect waves-dark null-btn" type="submit">Archive</button>
                </form>
                @endcan
                @can('update', $invoice)
                @if(!$invoice->isLocked())
                <a href="{{ route('invoice.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="btn light-blue waves-effect waves-dark">
                    Edit
                </a>
                @endif
                @endcan
                @can('delete', $invoice)
                <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn btn red waves-effect waves-dark">
                    Delete
                </a>
                @endcan
            </div>
        </div>
        @endcan
        @can('update', $invoice)
        <div class="fixed-action-btn toolbar mobile-only">
            <a class="btn-floating btn-large btn-large red">
                <i class="large material-icons">menu</i>
            </a>
            <ul>
                @can('create', \App\Models\Payment::class)
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Log Payment">
                    <a class="btn btn-link waves-effect waves-dark" href="{{ route('payment.create', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}">
                        <i class="material-icons">attach_money</i>
                    </a>
                </li>
                @endcan
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Invoice">
                    <form method="post" action="{{ route('invoice.duplicate', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form">
                        {{ csrf_field() }}
                        <button class="btn blue darken-3 waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">control_point_duplicate</i>
                        </button>
                    </form>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Send Notification">
                    <form method="post" action="{{ route('invoice.send', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form">
                        {{ csrf_field() }}
                        <button class="btn blue-grey waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">contact_mail</i>
                        </button>
                    </form>
                </li>
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Archive Invoice">
                    <form method="post" action="{{ route('invoice.archive', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="null-form">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                        <button class="btn amber darken-2 waves-effect waves-dark null-btn" type="submit">
                            <i class="material-icons">archive</i>
                        </button>
                    </form>
                </li>
                @if(!$invoice->isLocked())
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Invoice">
                    <a href="{{ route('invoice.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="btn light-blue waves-effect waves-dark">
                        <i class="material-icons">edit</i>
                    </a>
                </li>
                @endif
                @can('delete', $invoice)
                <li class="tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Invoice">
                    <a href="#" data-id="{{ $invoice->id }}" class="invoice-delete-btn btn red waves-effect waves-dark">
                        <i class="material-icons">delete</i>
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        @endcan
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
                        <dd>{{ $client->crn ?? '-' }}
                        <dt>Contact Name</dt>
                        <dd>{{ $client->contactname ?? '-' }}</dd>
                        <dt>Contact Email</dt>
                        <dd>{{ $client->contactemail ?? '-' }}</dd>
                        <dt>Contact Phone</dt>
                        <dd>{{ $client->contactphone ?? '-' }}</dd>
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
                            @elseif ($invoice->status == App\Models\Invoice::STATUS_WRITTENOFF)
                                <span class="alt-badge grey">{{ $invoice->statustext() }}</span>
                            @endif
                        </dd>
                    </dl>
                </div>
                @if($event)
                <h3>Recurring Details</h3>
                <div id="recurring-details-panel" class="card-panel">
                    <dl>
                        <dt>Repeats</dt>
                        <dd>Every {{ $event->time_interval . ' ' . ucwords($event->time_period) }}</dd>
                        <dt>Until</dt>
                        <dd>
                            @if($event->until_type === 'never')
                                {{ 'The End of Time' }}
                            @elseif($event->until_type == 'occurence')
                                {{ 'After ' . $event->until_meta . ' Occurences' }}
                            @elseif($event->until_type == 'date')
                                {{ 'On ' . \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->until_meta)->format('d F, Y') }}
                            @endif
                        </dd>
                    </dl>
                </div>
                @endif
                <h3>Change History</h3>
                <div id="change-history-container" class="change-history-container">
                    <div class="single-history-wrapper">
                        <div class="card single-history">
                            <h6>Date/Time</h6>
                            <p class="mtop20">{{ $invoice->updated_at->format('d F, Y') }}</p>
                            <p>{{ $invoice->updated_at->format('h:i:s a') }}</p>
                            <span class="alt-badge info mtop20">Current Version</span>
                            @can('update', $invoice)
                            @if(!$invoice->isLocked())<a href="{{ route('invoice.edit', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="btn btn-theme full-width mtop20">Edit</a>@endif
                            @else
                            <a href="{{ route('invoice.show', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="btn btn-theme full-width mtop20">View</a>
                            @endcan
                        </div>
                    </div>
                    @foreach($histories as $key => $history)
                        <div class="single-history-wrapper">
                            <div class="card single-history">
                                <h6>Date/Time</h6>
                                <p class="mtop20">{{ $history->updated_at->format('d F, Y') }}</p>
                                <p>{{ $history->updated_at->format('h:i:s a') }}</p>
                                <span class="alt-badge warning mtop20">Past Version</span>
                                <a href="{{ route('invoice.old.show', [ 'oldinvoice' => $history->id, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="btn btn-theme full-width mtop20">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <h3>Payment History</h3>
                <div id="payment-history-container" class="payment-history-container">
                    <div class="card-panel flex">
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
                                        <td>{{ $payment->receiveddate->format('d F, Y') }}</td>
                                        <td>{{ $payment->mode }}</td>
                                        <td>S${{ $payment->money_format }}</td>
                                        <td><span class="alt-badge teal lighten-1">{{ $payment->percentage }}%</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($siblings)
                <h3>Related Invoices</h3>
                <div id="payment-history-container" class="payment-history-container">
                    <div class="card-panel flex">
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siblings as $key => $sibling)
                                    <tr>
                                        <td>{{ $sibling->nice_invoice_id }}</td>
                                        <td>{{ $sibling->date->format('d F, Y')  }}</td>
                                        <td>S${{ $sibling->calculatetotal() }}</td>
                                        <td><a href="{{ route('invoice.show', [ 'invoice' => $sibling->id, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="btn btn-link waves-effect waves-dark tooltipped" data-position="top" data-delay="50" data-tooltip="View Invoice">View</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
            @include('partials/invoice', ['invoice_title' => 'Invoice', 'class' => 's12 l8'])
        </div>
    </div>
    @can('delete', $invoice)
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
    @endcan

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
                let height = $('#change-history-container').outerHeight() + 10;
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

            @can('delete', $invoice)
            $('#invoice-action-container').on('click', '.invoice-delete-btn', function (event) {
                event.preventDefault();
                let invoiceid = $(this).attr('data-id');
                $('#delete-invoice-form').attr('action', '/invoice/' + invoiceid + '/destroy');
                $('#delete-confirmation').modal('open');
            });

            $('.fixed-action-btn').on('click', '.invoice-delete-btn', function (event) {
                event.preventDefault();
                let invoiceid = $(this).attr('data-id');
                $('#delete-invoice-form').attr('action', '/invoice/' + invoiceid + '/destroy');
                $('#delete-confirmation').modal('open');
            });
            @endcan

            $('#change-history-container').slick({
                // normal options...
                infinite: false,
                arrows: true,
                dots: true,
                slidesToShow: 2,
                slidesToScroll: 2,
                adaptiveHeight: false,
                prevArrow: '<button type="button" class="slick-prev"><i class="material-icons md-36">keyboard_arrow_left</i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="material-icons md-36">keyboard_arrow_right</i></button>',
                responsive: [
                    {
                        breakpoint: 1650,
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
                    url: "{{ route('invoice.share', [ 'invoice' => $invoice, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}",
                })
                .done(function(data) {
                    $('#shared-link').val("{{ route('invoice.token') }}?token=" + data);
                    $('#shared-details').modal('open');
                });
            }
        });
    </script>
@stop