class Unicorn {
    constructor(enforcer) {
        throw new Error('Cannot construct singleton');
    }

    static initPhoneInput(selector, initialCountry = "sg")
    {
        $(selector).intlTelInput({
            initialCountry: initialCountry,
            utilsScript: "/assets/js/utils.js"
        });

        $(selector).focusin(function() {
            $(this).parent().siblings('.label-validation').addClass('theme-text');
        });

        $(selector).focusout(function() {
            $(this).parent().siblings('.label-validation').removeClass('theme-text');
        });
    }

    static initSelectize(selector)
    {
        $(selector).selectize({
            onChange: function(value, isOnInitialize) {
                this.$input.parsley().validate();
            }
        });
    }

    static initDatepicker(selector, yearRangeStart, yearRangeEnd, defaultDate)
    {
        $(selector).datepicker({
            autoClose: 'false',
            format: 'd mmmm, yyyy',
            yearRange: [yearRangeStart, yearRangeEnd],
            defaultDate: defaultDate,
            setDefaultDate: true,
            onSelect: function() {
                // let date = $(this)[0].formats.yyyy() + '-' + $(this)[0].formats.mm() + '-' + $(this)[0].formats.dd()
                // $('#receiveddate').val(date);
            }
        });
    }

    static initTrumbowyg(selector)
    {
        $(selector).trumbowyg({
            svgPath: '/assets/fonts/trumbowygicons.svg',
            removeformatPasted: true,
            resetCss: true,
            autogrow: true,
        });
    }

    static initNewItem(count, elementContainer, modelType, itemOptions) {
        let item = '<div id="' + modelType + '_item_' + count + '" class="card-panel"><div class="row"><div class="input-field col s12 l8"> <select id="item_name_' + count + '" name="item_name[]" class="item-list-selector" data-parsley-required="true" data-parsley-trigger="change"><option disabled="" selected="selected" value="">Pick an Item or Create a new one</option> </select> <label for="item_name_' + count + '" class="label-validation">Name</label> <span class="helper-text"></span></div><div class="input-field col s6 l2"> <input id="item_quantity_' + count + '" name="item_quantity[]" class="item-quantity-input" type="number" data-parsley-required="true" data-parsley-trigger="change" data-parsley-min="1" placeholder="Item Quantity"> <label for="item_quantity_' + count + '" class="label-validation">Quantity</label> <span class="helper-text"></span></div><div class="input-field col s6 l2"> <input id="item_price_' + count + '" name="item_price[]" class="item-price-input" type="number" step="0.01" data-parsley-required="true" data-parsley-trigger="change" placeholder="Item Price"> <label for="item_price_' + count + '" class="label-validation">Price</label> <span class="helper-text"></span></div><div class="input-field col s12 mtop30"><textarea id="item_description_' + count + '" name="item_description[]" class="item-description-textarea trumbowyg-textarea" data-parsley-required="false" data-parsley-trigger="change" placeholder="Item Description"></textarea><label for="item_description_' + count + '" class="label-validation">Description</label> <span class="helper-text"></span></div></div><div class="row"> <button data-id="false" data-count="' + count + '" class="' + modelType + '-item-delete-btn btn waves-effect waves-light col s12 m3 offset-m9 red">Delete</button></div></div>';
        $('#' + elementContainer).append(item);
        this.initItemElement(itemOptions);
        $('html, body').animate({
            scrollTop: $("#" + modelType + "_item_" + count).offset().top
        }, 500, 'linear');
    }

    static initListener(parent_selector, selector, trigger = 'click', callback = null)
    {
        $(parent_selector).on(trigger, selector, function (event) {
            typeof callback === 'function' && callback(event, $(this));
        });
    }

    static initItemConfirmationTrigger(parent_selector, selector, modal_selector, buttonSelector, trigger = 'click')
    {
        this.initListener(parent_selector, selector, trigger, function (event) {
            event.preventDefault();
            $(modal_selector).modal('open');

            let itemid = $(this).attr('data-id');
            let count = $(this).attr('data-count');

            $(modal_selector).children().children(buttonSelector).attr('data-id', itemid);
            $(modal_selector).children().children(buttonSelector).attr('data-count', count);
        });
    }

    static executeItemDeleteTrigger(parent_selector, selector, modelType, trigger = 'click')
    {
        this.initListener(parent_selector, selector, trigger, function (event) {
            event.preventDefault();

            let itemid = $(this).attr('data-id');
            let count = $(this).attr('data-count');

            if (itemid == "false") {
                $('#' + modelType + '_item_' + count).remove();
                $(parent_selector).modal('close');
            }
        });
    }

    static initConfirmationTrigger(parent_selector, selector, fqdn, model, model_action, modal_selector, modal_form_selector, trigger = 'click')
    {
        this.initListener(parent_selector, selector, trigger, function (event) {
            event.preventDefault();
            let dataid = $(this).attr('data-id');
            $(modal_form_selector).attr('action', '/' + fqdn + '/' + model + '/' + dataid + '/' + model_action);
            $(modal_selector).modal('open');
        });
    }

    static initPageSearch(selector, selector_context)
    {
        let inputBox = $(selector);
        let instance = $(selector_context);
        // let context = document.querySelector(selector_context);
        // let instance = new Mark(context);

        inputBox.on("input", function() {
            let term = $(this).val();
            instance.unmark().show();
            if (term != "") {
                instance.mark(term, {
                    done: function() {
                        instance.not(":has(mark)").hide();
                    }
                });
            }
        });
    }

    static initImageUpload(selector, upload_selector, display_selector)
    {
        $(upload_selector).click(function(){
            $(selector).click();
        });

        $(selector).on("change", function()
        {
            let files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test( files[0].type)){ // only image file
                let reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function(){ // set image data as background of div
                    $(display_selector).attr("src", this.result);
                }
            }
        });
    }

    static initParsleyValidation(selector, callback = null)
    {
        $(selector).parsley({
            successClass: 'valid',
            errorClass: 'invalid',
            errorsContainer: function (velem) {
                let $errelem = velem.$element.siblings('span.helper-text');
                $errelem.attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                return true;
            },
            errorsWrapper: '',
            errorTemplate: ''
        })
            .on('field:validated', function(velem) {
            })
            .on('field:success', function(velem) {
                if (velem.$element.is(':radio'))
                {
                    velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('invalid').addClass('valid');
                }
                else if (velem.$element.is('select')) {
                    velem.$element.siblings('.selectize-control').removeClass('invalid').addClass('valid');
                }
                else if (velem.$element.is('.trumbowyg-textarea'))
                {
                    velem.$element.parentsUntil('.row').find('.trumbowyg-box').removeClass('invalid').addClass('valid');
                    velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('invalid').addClass('valid');
                }
                else if (velem.$element.is('.phone-input'))
                {
                    velem.$element.parent('').siblings('span.helper-text').removeClass('invalid').addClass('valid');
                    velem.$element.val(velem.$element.intlTelInput("getNumber"));
                }
            })
            .on('field:error', function(velem) {
                if (velem.$element.is(':radio'))
                {
                    velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('valid').addClass('invalid');
                    velem.$element.parentsUntil('.row').find('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                }
                else if (velem.$element.is('select')) {
                    velem.$element.siblings('.selectize-control').removeClass('valid').addClass('invalid');
                }
                else if(velem.$element.is('.trumbowyg-textarea')) {
                    velem.$element.parentsUntil('.row').find('.trumbowyg-box').removeClass('valid').addClass('invalid');
                    velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('valid').addClass('invalid');
                    velem.$element.parentsUntil('.row').find('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                }
                else if (velem.$element.is('.phone-input'))
                {
                    velem.$element.parent('').siblings('span.helper-text').removeClass('valid').addClass('invalid');
                    velem.$element.parent('').siblings('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                }
            })
            .on('form:submit', function(velem) {
                typeof callback === 'function' && callback();
            });
    }

    static retrieveItemTemplate(baseEndpoint, itemTemplateId, element, callback) {
        if (typeof itemTemplateId !== typeof undefined && itemTemplateId !== false) {
            $.ajax({
                type: "GET",
                url: baseEndpoint + "/itemtemplate/" + itemTemplateId + "/retrieve",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
                .done(function(data) {
                    callback(element, data);
                })
                .fail(function(jqXHR, textStatus) {
                    console.log(jqXHR);
                    console.log(textStatus);
                })
                .always(function() {
                    console.log("finish");
                });
        }
    }

    static setItemTemplate(element, itemtemplate) {
        element.val(itemtemplate.name);
        let quantityElement = element.parentsUntil(".card-panel").find('.item-quantity-input');
        let priceElement = element.parentsUntil(".card-panel").find('.item-price-input');
        let descriptionElement = element.parentsUntil(".card-panel").find('.item-description-textarea');

        quantityElement.val(itemtemplate.quantity);
        priceElement.val(itemtemplate.price);
        descriptionElement.trumbowyg('html', itemtemplate.description);
    }

    static initItemElement(options)
    {
        $('.trumbowyg-textarea').trumbowyg({
            svgPath: '/assets/fonts/trumbowygicons.svg',
            removeformatPasted: true,
            resetCss: true,
            autogrow: true,
        });

        //Explicit selection otherwise will change the select into a multiple select if only selecting by css class
        $('select.item-list-selector').selectize({
            create: true,
            valueField: 'name',
            labelField: 'name',
            searchField: ['name'],
            onChange: function(value, isOnInitialize) {
                this.$input.parsley().validate();
            },
            options: options,
            render: {
                option: function(data) {
                    return '<div class="option" data-selectable="" data-id="' + data.id +'" data-value="' + data.name +'">' + data.name +'</div>';
                }
            }
        });
    }
}

Unicorn.type = 'Unicorn';

// If exporting with export default Unicorn, the class needs to be accessed via : Unicorn.default.donkey()
// export default Unicorn;

module.exports = Unicorn;