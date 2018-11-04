webpackJsonp([8],{

/***/ "./resources/assets/js/unicorn.js":
/***/ (function(module, __webpack_exports__) {

"use strict";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var instance = null;

var Unicorn = function () {
    function Unicorn(enforcer) {
        _classCallCheck(this, Unicorn);

        throw new Error('Cannot construct singleton');
    }

    _createClass(Unicorn, null, [{
        key: 'donkey',
        value: function donkey() {
            return 'donkey';
        }
    }, {
        key: 'initPhoneInput',
        value: function initPhoneInput(selector) {
            $(selector).intlTelInput({
                initialCountry: "sg",
                utilsScript: "/assets/js/utils.js"
            });

            $(selector).focusin(function () {
                $(this).parent().siblings('.label-validation').addClass('theme-text');
            });

            $(selector).focusout(function () {
                $(this).parent().siblings('.label-validation').removeClass('theme-text');
            });
        }
    }, {
        key: 'initConfirmationTrigger',
        value: function initConfirmationTrigger(parent_selector, selector, model, model_action, modal_selector, modal_form_selector) {
            var trigger = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : 'click';

            $(parent_selector).on(trigger, selector, function (event) {
                event.preventDefault();
                var dataid = $(this).attr('data-id');
                $(modal_form_selector).attr('action', '/' + model + '/' + dataid + '/' + model_action);
                $(modal_selector).modal('open');
            });
        }
    }, {
        key: 'initPageSearch',
        value: function initPageSearch(selector, selector_context) {
            var inputBox = $(selector);
            var context = $(selector_context);

            inputBox.on("input", function () {
                var term = $(this).val();
                context.unmark().show();
                if (term != "") {
                    console.log(term);
                    context.mark(term, {
                        done: function done() {
                            context.not(":has(mark)").hide();
                        }
                    });
                }
            });
        }
    }, {
        key: 'initImageUpload',
        value: function initImageUpload(selector, upload_selector, display_selector) {
            $(upload_selector).click(function () {
                $(selector).click();
            });

            $(selector).on("change", function () {
                var files = !!this.files ? this.files : [];
                if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

                if (/^image/.test(files[0].type)) {
                    // only image file
                    var reader = new FileReader(); // instance of the FileReader
                    reader.readAsDataURL(files[0]); // read the local file

                    reader.onloadend = function () {
                        // set image data as background of div
                        $(display_selector).attr("src", this.result);
                    };
                }
            });
        }
    }, {
        key: 'initParsleyValidation',
        value: function initParsleyValidation(selector) {
            $(selector).parsley({
                successClass: 'valid',
                errorClass: 'invalid',
                errorsContainer: function errorsContainer(velem) {
                    var $errelem = velem.$element.siblings('span.helper-text');
                    $errelem.attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                    return true;
                },
                errorsWrapper: '',
                errorTemplate: ''
            }).on('field:validated', function (velem) {}).on('field:success', function (velem) {
                if (velem.$element.is(':radio')) {
                    velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('invalid').addClass('valid');
                } else if (velem.$element.is('select')) {
                    velem.$element.siblings('.selectize-control').removeClass('invalid').addClass('valid');
                } else if (velem.$element.is('.phone-input')) {
                    velem.$element.parent('').siblings('span.helper-text').removeClass('invalid').addClass('valid');
                }
            }).on('field:error', function (velem) {
                if (velem.$element.is(':radio')) {
                    velem.$element.parentsUntil('.row').find('span.helper-text').removeClass('valid').addClass('invalid');
                    velem.$element.parentsUntil('.row').find('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                } else if (velem.$element.is('select')) {
                    velem.$element.siblings('.selectize-control').removeClass('valid').addClass('invalid');
                } else if (velem.$element.is('.phone-input')) {
                    velem.$element.parent('').siblings('span.helper-text').removeClass('valid').addClass('invalid');
                    velem.$element.parent('').siblings('span.helper-text').attr('data-error', window.Parsley.getErrorMessage(velem.validationResult[0].assert));
                }
            }).on('form:submit', function (velem) {
                if (velem.$element.is('.phone-input')) {
                    velem.$element.val(velem.$element.intlTelInput("getNumber"));
                }
            });
        }
    }]);

    return Unicorn;
}();
//
// // function Unicorn() {};
// //
// // Unicorn.initPhoneInput = function(element_id)
// // {
// //
// // }

// function Unicorn() {
//     this.hello = function() {
//         return 'hello!';
//     }
//
//     this.goodbye = function() {
//         return 'goodbye!';
//     }
// }
//
// module.exports = Unicorn;

Unicorn.type = 'Unicorn';

// If exporting with export default Unicorn, the class needs to be accessed via : Unicorn.default.donkey()
// export default Unicorn;

module.exports = Unicorn;

/***/ }),

/***/ 2:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/assets/js/unicorn.js");


/***/ })

},[2]);