var App = (function () {
  'use strict';

  App.IconsFilter = function( ){
    
    //Select2
    $(".select2").select2({
      width: '100%'
    });

    //Icons search filter
    var icons = [];
    var appContainer = $('.be-icons-list');
    var categories = $(".icon-category", appContainer);
    var appSearchInput = $('.input-search input', appContainer);
    var appCategorySelect = $('#icon-category', appContainer);

    categories.each(function(i, el){
      $('.icon-class',el).each(function(index, iconEl){
        var icon = {
          name: iconEl.innerHTML,
          element: iconEl,
          category: el
        }
        icons.push( icon );
      });
    });

    appSearchInput.on('keyup',function(){
      var visibleCategories = [];
      var value = $(this).val().toLowerCase();

      if( appCategorySelect.val() == 'all' ){
        categories.show();
      }else{
        $('#' + appCategorySelect.val()).show();
      }

      if( value ){
        $('.icon-visible', categories).removeClass('icon-visible');

        var results = $.grep(icons,function(el, i){
          var search = el.name.search( value ) > -1;
          if( search && visibleCategories.indexOf( el.category ) < 0 ){
            visibleCategories.push(el.category);
          }

          return search;
        });

        $.each(results, function(i, o){
          $(o.element).parents('.col-xs-6').addClass('icon-visible');
        });

        appContainer.addClass('hide-icons');
        categories.not(visibleCategories).hide().addClass('icon-category--hide-icons');
      }else{
        appContainer.removeClass('hide-icons');
      }
    });

    //Icon category filter
    appCategorySelect.on('change',function(){
      var val = $(this).val();

      if( val == 'all' ){
        categories.show();
      }else{
        categories.hide();
        $('#' + val).show();
      }
    }); 
  };

  return App;
})(App || {});
