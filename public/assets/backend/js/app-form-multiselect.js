var App = (function () {
	'use strict';

	App.formMultiselect = function( ){

	//Bootstrap multiselect init
 	$('#example1').multiselect();
		
    $('#example2').multiselect();
		
    $('#example3').multiselect({
        buttonClass: 'btn btn-link'
    });

    $('#example4').multiselect({
        buttonClass: 'btn btn-default btn-sm'
    });

    $('#example6').multiselect();

    $('#example9').multiselect({
        onChange:function(element, checked){
            alert('Change event invoked!');
            console.log(element);
        }
    });
		
    for (var i = 1; i <= 100; i++) {
        $('#example11').append('<option value="' + i + '">Options ' + i + '</option>');
    }
    $('#example11').multiselect({
        maxHeight: 150
    })
		
    $('#example13').multiselect();

    $('#example14').multiselect({
        buttonWidth: '500px',
        buttonText: function(options) {
            if (options.length === 0) {
                return 'None selected';
            }
            else {
                var selected = '';
                options.each(function() {
                    selected += $(this).text() + ', ';
                });
                return selected.substr(0, selected.length -2) + ' <b class="caret"></b>';
            }
        }
    });
		
    $('#example16').multiselect({
        onChange: function(option, checked) {
                  if (checked === false) {
                      $('#example16').multiselect('select', option.val());
                  }
        }
    });

    $('#example19').multiselect();

    $('#example20').multiselect({
        selectedClass: null
    });

    $('#example23').multiselect();

    $('#example24').multiselect();

    $('#example25').multiselect({
    	dropRight: true,
    	buttonWidth: '300px'
    });

    $('#example27').multiselect({
    	includeSelectAllOption: true
    });

    $('#example28').multiselect({
    	enableFiltering: true,
        maxHeight: 150
    });
              
    $('#example32').multiselect();
              
    $('#example39').multiselect({
      includeSelectAllOption: true,
    	enableCaseInsensitiveFiltering: true
    });
              
    $('#example41').multiselect({
    	includeSelectAllOption: true
    });

    //Multiselect init

    $('#my-select').multiSelect()
    $('#pre-selected-options').multiSelect();
    $('#callbacks').multiSelect({
      afterSelect: function(values){
        alert("Select value: "+values);
      },
      afterDeselect: function(values){
        alert("Deselect value: "+values);
      }
    });
    $('#optgroup').multiSelect({ selectableOptgroup: true });
    $('#disabled-attribute').multiSelect();
    $('#custom-headers').multiSelect({
      selectableHeader: "<div class='custom-header'>Selectable items</div>",
      selectionHeader: "<div class='custom-header'>Selection items</div>",
    });
    $('#searchable').multiSelect({
      selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Search'>",
      selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Search'>",
      afterInit: function(ms){
        var that = this,
            $selectableSearch = that.$selectableUl.prev(),
            $selectionSearch = that.$selectionUl.prev(),
            selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
            selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

        that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
        .on('keydown', function(e){
          if (e.which === 40){
            that.$selectableUl.focus();
            return false;
          }
        });

        that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
        .on('keydown', function(e){
          if (e.which == 40){
            that.$selectionUl.focus();
            return false;
          }
        });
      },
      afterSelect: function(){
        this.qs1.cache();
        this.qs2.cache();
      },
      afterDeselect: function(){
        this.qs1.cache();
        this.qs2.cache();
      }
    });
	};

  return App;
})(App || {});