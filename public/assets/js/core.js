webpackJsonp([9],{

/***/ "./resources/assets/js/core.js":
/***/ (function(module, exports, __webpack_require__) {

"use strict";


window.Parsley.addValidator('phoneFormat', {
    requirementType: 'string',
    validateString: function validateString(value, elementid) {
        if ($(elementid).intlTelInput("isValidNumber")) {
            return true;
        } else {
            return false;
        }
    },
    messages: {
        en: 'This is an invalid phone number format'
    }
});

/***/ }),

/***/ 1:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/assets/js/core.js");


/***/ })

},[1]);