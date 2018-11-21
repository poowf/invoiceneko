class Unicorn {
    constructor(enforcer) {
        throw new Error('Cannot construct singleton');
    }

    static initPhoneInput(selector)
    {
        $(selector).intlTelInput({
            initialCountry: "sg",
            utilsScript: "/assets/js/utils.js"
        });

        $(selector).focusin(function() {
            $(this).parent().siblings('.label-validation').addClass('theme-text');
        });

        $(selector).focusout(function() {
            $(this).parent().siblings('.label-validation').removeClass('theme-text');
        });
    }

    static initConfirmationTrigger(parent_selector, selector, fqdn, model, model_action, modal_selector, modal_form_selector, trigger = 'click')
    {
        $(parent_selector).on(trigger, selector, function (event) {
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

    static initParsleyValidation(selector)
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
                if (velem.$element.is('.phone-input'))
                {
                    velem.$element.val(velem.$element.intlTelInput("getNumber"));
                }
            });
    }
}

Unicorn.type = 'Unicorn';

// If exporting with export default Unicorn, the class needs to be accessed via : Unicorn.default.donkey()
// export default Unicorn;

module.exports = Unicorn;