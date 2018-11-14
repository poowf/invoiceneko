@extends("layouts.default", ['page_title' => 'Item Template | Edit'])

@section("head")
    <style>
    </style>
@stop

@section("content")
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h3>Update Item Template</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <form id="edit-itemtemplate" method="post" enctype="multipart/form-data">
                    <div class="card-panel">
                        <div class="row">
                            <div class="input-field col s12 l8">
                                <input id="name" name="name" type="text" data-parsley-required="true" data-parsley-trigger="change" value="{{ $itemtemplate->name }}" placeholder="Item Name">
                                <label for="name" class="label-validation">Name</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6 l2">
                                <input id="quantity" name="quantity" type="number" data-parsley-required="true" data-parsley-trigger="change" value="{{ $itemtemplate->quantity }}" placeholder="Item Quantity">
                                <label for="quantity" class="label-validation">Quantity</label>
                                <span class="helper-text"></span>
                            </div>
                            <div class="input-field col s6 l2">
                                <input id="price" name="price" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" value="{{ $itemtemplate->price }}" placeholder="Item Price">
                                <label for="price" class="label-validation">Price</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="description" name="description" class="trumbowyg-textarea" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Description">{{ $itemtemplate->description }}</textarea>
                                <label for="description" class="label-validation">Description</label>
                                <span class="helper-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <button class="btn waves-effect waves-light col s12 m3 offset-m9" type="submit" name="action">Update</button>
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
            $('.trumbowyg-textarea').trumbowyg({
                svgPath: '/assets/fonts/trumbowygicons.svg',
                removeformatPasted: true,
                resetCss: true,
                autogrow: true,
            });

            Unicorn.initParsleyValidation('#edit-itemtemplate');
        });
    </script>
@stop