var App = (function () {
  'use strict';
  
  App.mapsVector = function( ){

    var color1 = App.color.primary;
    var color2 = App.color.success;
    var color3 = App.color.danger;
    var color4 = App.color.warning;
    var color5 = App.color.success;
    var color6 = App.color.primary;
    var color7 = tinycolor( App.color.grey ).lighten( 5 ).toString();
    var color8 = App.color.danger;

    //Maps
    $('#usa-map').vectorMap({
      map: 'us_merc_en',
      backgroundColor: 'transparent',
      regionStyle: {
        initial: {
          fill: color1,
        },
        hover: {
          "fill-opacity": 0.8
        }
      }
    });

    $('#france-map').vectorMap({
      map: 'fr_merc_en',
      backgroundColor: 'transparent',
      regionStyle: {
        initial: {
          fill: color2,
        },
        hover: {
          "fill-opacity": 0.8
        }
      }
    });

    $('#uk-map').vectorMap({
      map: 'uk_mill_en',
      backgroundColor: 'transparent',
      regionStyle: {
        initial: {
          fill: color3,
        },
        hover: {
          "fill-opacity": 0.8
        }
      }
    });
      
    $('#chicago-map').vectorMap({
      map: 'us-il-chicago_mill_en',
      backgroundColor: 'transparent',
      regionStyle: {
        initial: {
          fill: color4,
        },
        hover: {
          "fill-opacity": 0.8
        }
      }
    });
      
    $('#australia-map').vectorMap({
      map: 'au_mill_en',
      backgroundColor: 'transparent',
      regionStyle: {
        initial: {
          fill: color5,
        },
        hover: {
          "fill-opacity": 0.8
        }
      }
    });
      
    $('#india-map').vectorMap({
      map: 'in_mill_en',
      backgroundColor: 'transparent',
      regionStyle: {
        initial: {
          fill: color6,
        },
        hover: {
          "fill-opacity": 0.8
        }
      }
    });
      
    $('#vector-map').vectorMap({
      map: 'map',
      backgroundColor: 'transparent',
      regionStyle: {
          initial: {
            fill: color7,
            "fill-opacity": 0.8,
            stroke: 'none',
            "stroke-width": 0,
            "stroke-opacity": 1
          },
          hover: {
            "fill-opacity": 0.8
          }
        },
      markerStyle:{
        initial:{
          r: 10
        }
      },
      markers: [
          {coords: [100, 100], name: 'Marker 1', style: {fill: '#acb1b6',stroke:'#cfd5db',"stroke-width": 3}},
          {coords: [200, 200], name: 'Marker 2', style: {fill: '#acb1b6',stroke:'#cfd5db',"stroke-width": 3}},
      ]
    });

    $('#canada-map').vectorMap({
      map: 'ca_lcc_en',
      backgroundColor: 'transparent',
      regionStyle: {
        initial: {
          fill: color8,
        },
        hover: {
          "fill-opacity": 0.8
        }
      }
    });

  };

  return App;
})(App || {});
