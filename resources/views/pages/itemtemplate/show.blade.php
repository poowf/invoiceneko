@extends("layouts.default", ['page_title' => 'Item Template | View'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s6">
                <h3>Item Template Details</h3>
            </div>
            <div class="col s6 right">
                @can('update', $itemTemplate)
                <a href="{{ route('itemtemplate.edit', [ 'itemtemplate' => $itemTemplate, 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ] ) }}" class="btn light-blue waves-effect waves-dark mtop30">Edit</a>
                @endcan
                @can('delete', $itemTemplate)
                <a href="#" data-id="{{ $itemTemplate->id }}" class="btn btn-link waves-effect waves-dark itemtemplate-delete-btn mtop30">Delete</a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <dl>
                        <dt>Name</dt>
                        <dd>{{ $itemTemplate->name ?? '-'}}</dd>
                        <dt>Quantity</dt>
                        <dd>{{ $itemTemplate->quantity ?? '-' }}</dd>
                        <dt>Price</dt>
                        <dd>{{ $itemTemplate->price ?? '-' }}</dd>
                        <dt>Description</dt>
                        <dd>{!! $itemTemplate->description !!}</dd>
                    </dl>
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
            Unicorn.initConfirmationTrigger('.container', '.itemtemplate-delete-btn', '{{ \App\Library\Poowf\Unicorn::getCompanyKey() }}', 'itemtemplate', 'destroy', '#delete-confirmation', '#delete-itemtemplate-form');
        });
    </script>
@stop