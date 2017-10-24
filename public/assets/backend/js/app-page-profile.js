var App = (function () {
  'use strict';

  App.pageProfile = function( ){

    function bar_chart(){

      var color1 = App.color.primary;
      var color2 = tinycolor( App.color.primary ).lighten( 22 ).toString();

      var plot_statistics = $.plot($("#bar-chart1"), [
        {
          data: [
            [0, 7], [1, 13], [2, 17], [3, 20], [4, 26], [5, 37], [6, 35], [7, 28], [8, 38], [9, 38], [10, 32], [11, 25]
          ],
          label: "Page Views"
        },
        {
          data: [
            [0, 15], [1, 10], [2, 15], [3, 25], [4, 30], [5, 29], [6, 25], [7, 33], [8, 45], [9, 43], [10, 38], [11, 36]
          ],
          label: "Unique Visitor"
        }
      ], {
        series: {
          bars: {
            order: 2,
            align: 'center',
            show: true,
            barWidth: 0.3,
            lineWidth: 0.7, 
            fill: true,
            fillColor: {
              colors: [{
                opacity: 1
              }, {
                opacity: 1
              }
              ]
            } 
          },
          shadowSize: 2
        },
        legend:{
          show: false
        },
        grid: {
          show: false
        },
        colors: [color1, color2],
        xaxis: {
          ticks: 11,
          tickDecimals: 0
        },
        yaxis: {
          ticks: 4,
          tickDecimals: 0
        }
      });
    }

    bar_chart();
    
  };

  return App;
})(App || {});
