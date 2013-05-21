<!doctype html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Document</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="../modules/highcharts.js"></script>
<script src="../modules/exporting.js"></script>
<script type="text/javascript">                        
    var chart;
    var datos;
    var url = 'include/incjson.php?tra=anio';
    $.getJSON(url, function (dat) {
    	datos = dat;
    });
    $(document).ready(function() {
    chart = new Highcharts.Chart({

    chart: {
        renderTo: 'container',
        type: 'column',
        marginRight: 130,
        marginBottom: 25
        },
                        title: {
                            text: 'Monthly Average Temperature',
                            x: -20 //center
                        },
                        subtitle: {
                            text: 'Source: WorldClimate.com',
                            x: -20
                        },
                        xAxis: {
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                        },
                        yAxis: {

                            title: {

                                text: 'Temperature (&deg;C)'

                            },
                            plotLines: [{
                                    value: 0,
                                    width: 1,
                                    color: '#808080'
                                }]
                        },
                        tooltip: {
                            formatter: function() {
                                return '<b>'+ this.series.name +'</b><br/>'+
                                    this.x +': '+ this.y +'&deg;C';
                            }

                       },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -10,
                            y: 100,
                            borderWidth: 0
                       },
                        series: datos
                    });
                });
        </script>
</head>
<body>
	
<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
</body>
</html>