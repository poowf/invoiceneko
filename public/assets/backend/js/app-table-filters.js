var App = (function () {
	'use strict';

	App.tableFilters = function( ){
		//Put your code here

		//Bootstrap Slider
    $('.bslider').bootstrapSlider({
    	tooltip: 'hide'
    });

    $("#milestone_slider").slider().on("slide",function(e){
      var v1 = e.value[0];
      var v2 = e.value[1];

      $("#slider-value").html( v1 + "%" + " - " + v2 + "%");

    });

    //Select2
    $(".select2").select2({
      width: '100%'
    });
    
    //Select2 tags
    $(".tags").select2({tags: true, width: '100%'});
    
    //Js Code
    $(".datetimepicker").datetimepicker({
    	autoclose: true,
    	componentIcon: '.mdi.mdi-calendar',
    	navIcons:{
    		rightIcon: 'mdi mdi-chevron-right',
    		leftIcon: 'mdi mdi-chevron-left'
    	}
    });
	};

	return App;
})(App || {});