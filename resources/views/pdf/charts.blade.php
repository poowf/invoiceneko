<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="font-family: sans-serif; overflow-x: hidden;">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="{{ asset('/assets/css/chartist.min.css') }}" rel="stylesheet" type="text/css">
        <style>
            .ct-chart {
                position: relative;
            }
            .ct-legend {
                position: relative;
                z-index: 10;
                list-style: none;
                text-align: center;
            }
            .ct-legend li {
                position: relative;
                padding-left: 23px;
                margin-right: 10px;
                margin-bottom: 3px;
                cursor: pointer;
                display: inline-block;
            }
            .ct-legend li:before {
                width: 12px;
                height: 12px;
                position: absolute;
                left: 0;
                content: '';
                border: 3px solid transparent;
                border-radius: 2px;
            }
            .ct-legend li.inactive:before {
                background: transparent;
            }
            .ct-legend.ct-legend-inside {
                position: absolute;
                top: 0;
                right: 0;
            }
            .ct-legend.ct-legend-inside li{
                display: block;
                margin: 0;
            }
            .ct-legend .ct-series-0:before {
                background-color: #d70206;
                border-color: #d70206;
            }
            .ct-legend .ct-series-1:before {
                background-color: #f05b4f;
                border-color: #f05b4f;
            }
            .ct-legend .ct-series-2:before {
                background-color: #f4c63d;
                border-color: #f4c63d;
            }
            .ct-legend .ct-series-3:before {
                background-color: #d17905;
                border-color: #d17905;
            }
            .ct-legend .ct-series-4:before {
                background-color: #453d3f;
                border-color: #453d3f;
            }
            .ct-label.ct-vertical.ct-start {
                width: 35px !important;
                height: 30px !important;
                padding-top: 30px;
                display: inline-block;
            }
            td {
                vertical-align: top;
            }
        </style>
    </head>
    <body style="margin: 0; font-family: 'Roboto', Arial, sans-serif;font-size: 13px;">
        <div class="chart" style="background-color: #ffffff; padding: 50px 50px 20px; color: #8c8c8c;">
            <div class="row">
                <table>
                    <tr>
                        <td style="padding: 20px 10px;">
                            <div style="width: 400px; display: inline; ">
                                <h1>Upcoming Appointment</h1>
                                <h3>Dr. Lee Kong Shen</h3>
                                <h3>Date: 01/12/2017</h3>
                                <h3>Location: <br>Gleneagles Hospital <br>Level 3, Clinic G Room 502</h3>
                            </div>
                        </td>
                        <td style="padding: 20px 10px; padding-left: 100px;">
                            <div style="width: 400px; display: inline;">
                                <h1>Calories Burnt</h1>
                                <h2>350 Calories</h2>
                                <h3>Breakdown</h3>
                                <div class="calorie-ct-chart ct-chart" style="width: 400px;"></div>
                            </div>
                        </td>
                    </tr>
                </table>


            </div>
            <div class="row" style="text-align: center;">
                <h1>Blood Glucose Level</h1>
                <div class="bg-ct-chart ct-chart ct-perfect-fourth" style="width: 880px; height: 409px;"></div>
            </div>

            <div class="row" style="text-align: center;">
                <h1>A1C Quarterly Level</h1>
                <div class="a1c-ct-chart ct-chart ct-perfect-fourth" style="width: 880px; height: 409px;"></div>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('/assets/js/chartist.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/assets/js/chartist-plugin-legend.js') }}"></script>
        <script>
            var caloriedata = {
                series: [5, 3, 4]
            };

            new Chartist.Pie('.calorie-ct-chart', caloriedata,
            {
                labelInterpolationFnc: function(value) {
                    return Math.round(value / (5+3+4) * 100) + '%';
                },
                plugins: [
                    Chartist.plugins.legend({
                        legendNames: ['Running', 'Cycling', 'Swimming'],
                    })
                ]
            },
            [
                ['screen and (min-width: 640px)', {
                    chartPadding: 30,
                    labelOffset: 100,
                    labelDirection: 'explode',
                    labelInterpolationFnc: function(value) {
                        return value;
                    }
                }],
                ['screen and (min-width: 1024px)', {
                    labelOffset: 80,
                    chartPadding: 20
                }]
            ]);

            // All you need to do is pass your configuration as third parameter to the chart function
            new Chartist.Line('.bg-ct-chart', {
                labels: ["27/11", "28/11", "29/11", "30/11", "1/12", "2/12", "3/12"],
                series: [
                    [2, 2, 2, 2, 2, 2, 2],
                    [5, 9, 7, 8, 5, 3, 5]
                ]
            }, {
                low: 0,
                showArea: true,
                fullWidth: true,
                chartPadding: {
                    top: 40,
                    right: 30
                },
                axisY: {
                    labelInterpolationFnc: function(value) {
                        return value + " mg";
                    }
                },
                plugins: [
                    Chartist.plugins.legend({
                        legendNames: ['Recommended Level', 'Your Level'],
                    })
                ]
            });

            // All you need to do is pass your configuration as third parameter to the chart function
            new Chartist.Line('.a1c-ct-chart', {
                labels: ["01/2017", "03/2017", "06/2017", "09/2017", "12/2017"],
                series: [
                    [7, 7, 7, 7, 7],
                    [5, 9, 7, 8, 5]
                ]
            }, {
                low: 0,
                showArea: true,
                fullWidth: true,
                chartPadding: {
                    top: 40,
                    right: 50
                },
                axisY: {
                    labelInterpolationFnc: function(value) {
                        return value + " mg";
                    }
                },
                plugins: [
                    Chartist.plugins.legend({
                        legendNames: ['Recommended Level', 'Your Level'],
                    })
                ]
            });

        </script>
    </body>
</html>