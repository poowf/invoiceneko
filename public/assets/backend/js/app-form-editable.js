var App = (function () {
	'use strict';

	App.formEditable = function( ){
		//toggle `popup` / `inline` mode
		$.fn.editable.defaults.mode = 'popup';     
		
		//make username editable
		$('#username').editable();
		
		//make username editable
		$('#firstname').editable({
			validate: function(value) {
				if($.trim(value) == '') {
						return 'This field is required';
				}
			}
		});
			
		//make username editable
		$('#sex').editable({
			prepend: "not selected",
			source: [
				{value: 1, text: 'Male'},
				{value: 2, text: 'Female'}
			],
			display: function(value, sourceData) {
				var colors = {"": "gray", 1: "green", 2: "blue"},
				elem = $.grep(sourceData, function(o){return o.value == value;});
				if(elem.length) {
					$(this).text(elem[0].text).css("color", colors[value]);
				} else {
					$(this).empty();
				}
			}
		});

		//make group editable
		$('#group').editable({
			showbuttons: false,
			source: [
				{value: 1, text: 'Admin'},
				{value: 2, text: 'Support'},
				{value: 3, text: 'Operator'},
				{value: 4, text: 'Customer'},
				{value: 5, text: 'Service'}
			]
		});

		//make status editable
		$('#status').editable({
				type: 'select',
				title: 'Select status',
				placement: 'right',
				value: 2,
				source: [
						{value: 1, text: 'status 1'},
						{value: 2, text: 'status 2'},
						{value: 3, text: 'status 3'}
				]
		});

		//make dob editable
		$('#dob').editable({
			format: 'dd-mm-yyyy',
			viewformat: 'dd/mm/yyyy',
			datepicker: {
				weekStart: 1
			}
		});

		//make event editable
		$('#event').editable({
			 placement: 'right',
			 combodate: {
					 firstItem: 'name'
			 }
		});

		//make comments editable
		$('#comments').editable({
			showbuttons: 'bottom'
		});
			
		//make state2 editable
		$('#state2').editable({
			value: 'California',
			typeahead: {
				name: 'state',
				local: ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"]
			}
		});

		//make fruits editable
		$('#fruits').editable({
			pk: 1,
			limit: 3,
			source: [
				{value: 1, text: 'banana'},
				{value: 2, text: 'peach'},
				{value: 3, text: 'apple'},
				{value: 4, text: 'watermelon'},
				{value: 5, text: 'orange'}
			]
		});
	};

	return App;
})(App || {});