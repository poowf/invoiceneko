(function (factory) {
  /* global define */
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else if (typeof module === 'object' && module.exports) {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(window.jQuery);
  }
}(function ($) {

  //  - plugin is external module for customizing.
  $.extend($.summernote.plugins, {

    'beagle': function (context) {

      var layoutInfo = context.layoutInfo;
      var $toolbar = layoutInfo.toolbar;

      this.initialize = function ( ) {

        //Remove the .btn-sm class from toolbar
        $toolbar.find(".btn-sm").removeClass("btn-sm");

      };
    }
  });
}));