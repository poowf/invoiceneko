@extends("layouts.default", ['page_title' => 'Item Template | Create'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Create Item Template</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="create-itemtemplate" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12 l8">
                                <input id="name" name="name" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('name') }}" placeholder="Item Name">
                                <label for="name" class="label-validation">Name</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6 l2">
                                <input id="quantity" name="quantity" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('quantity') }}" placeholder="Item Quantity">
                                <label for="quantity" class="label-validation">Quantity</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6 l2">
                                <input id="price" name="price" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" value="{{ old('price') }}" placeholder="Item Price">
                                <label for="price" class="label-validation">Price</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="description" name="description" class="trumbowyg-textarea" data-parsley-required="false" data-parsley-trigger="change" placeholder="Item Description">{{ old('description') }}</textarea>
                                <label for="description" class="label-validation">Description</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section("scripts")
    <script type="text/javascript">
        "use strict";
        $(function() {
            Unicorn.initTrumbowyg('.trumbowyg-textarea');
            Unicorn.initParsleyValidation('#create-itemtemplate');
        });
    </script>
@stop