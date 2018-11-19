@extends("layouts.default", ['page_title' => 'Item Templates'])

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
        #itemtemplate-container {
            margin: 0px;
        }
    </style>
@stop

@section("content")
    <div class="wide-container">
        <div class="row">
            <div class="col s6">
                <h3>Item Templates</h3>
            </div>

            <div class="col s6 right mtop30">
                @can('create', \App\Models\ItemTemplate::class)
                <a href="{{ route('itemtemplate.create') }}" class="btn btn-link waves-effect waves-dark">Create</a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel search-panel">
                    <input id="search-input" class="card-input" name="search-input" type="search" placeholder="Search">
                </div>

                <div id="itemtemplate-container" class="row">
                    <div class="card-panel flex">
                        <table id="itemtemplates-table" class="responsive-table striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($itemtemplates as $key => $itemtemplate)
                                    <tr class="single-itemtemplate-row">
                                        <td>{{ $itemtemplate->name  }}</td>
                                        <td>{{ $itemtemplate->quantity }}</td>
                                        <td>{{ $itemtemplate->price }}</td>
                                        <td>
                                            @can('view', $itemtemplate)
                                            <a href="{{ route('itemtemplate.show', [ 'itemtemplate' => $itemtemplate->id ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="View Item Template"><i class="material-icons">remove_red_eye</i></a>
                                            @endcan
                                            @can('update', $itemtemplate)
                                            <form method="post" action="{{ route('itemtemplate.duplicate', [ 'itemtemplate' => $itemtemplate->id ] ) }}" class="null-form tooltipped" data-position="top" data-delay="50" data-tooltip="Duplicate Item Template">
                                                {{ csrf_field() }}
                                                <button class="null-btn" type="submit"><i class="material-icons">control_point_duplicate</i></button>
                                            </form>
                                            @endcan
                                            @can('update', $itemtemplate)
                                            <a href="{{ route('itemtemplate.edit', [ 'itemtemplate' => $itemtemplate->id ] ) }}" class="tooltipped" data-position="top" data-delay="50" data-tooltip="Edit Item Template"><i class="material-icons">mode_edit</i></a>
                                            @endcan
                                            @can('delete', $itemtemplate)
                                            <a href="#" data-id="{{ $itemtemplate->id }}" class="itemtemplate-delete-btn tooltipped" data-position="top" data-delay="50" data-tooltip="Delete Item Template"><i class="material-icons">delete</i></a>
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
    <div id="delete-confirmation" class="modal">
        <div class="modal-content">
            <p>Delete Item Template?</p>
        </div>
        <div class="modal-footer">
            <form id="delete-itemtemplate-form" method="post" class="null-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="modal-action waves-effect black-text waves-green btn-flat btn-deletemodal itemtemplate-confirm-delete-btn" type="submit">Delete</button>
            </form>
            <a href="javascript:;" class=" modal-action modal-close waves-effect black-text waves-red btn-flat btn-deletemodal">Cancel</a>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initConfirmationTrigger('#itemtemplate-container', '.itemtemplate-delete-btn', 'itemtemplate', 'destroy', '#delete-confirmation', '#delete-itemtemplate-form');
            Unicorn.initPageSearch('#search-input', '#itemtemplate-container .single-itemtemplate-row');
        });
    </script>
@stop