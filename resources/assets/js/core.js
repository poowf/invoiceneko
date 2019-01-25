"use strict";

window.Parsley
    .addValidator('phoneFormat', {
        requirementType: 'string',
        validateString: function(value, elementid) {
            if($(elementid).intlTelInput("isValidNumber"))
            {
                return true;
            }
            else
            {
                return false;
            }
        },
        messages: {
            en: 'This is an invalid phone number format'
        }
    });