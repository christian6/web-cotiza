<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Estaditicas</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/styleint.css">
  	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <script src="../modules/highcharts.js"></script>
    <script src="../modules/exporting.js"></script>
    <script>
    	$(function () {
    		var url = 'include/incjson.php?tra=anio';
    		var datos;
    		$.getJSON(url, function (dat) {
    			var datos = dat;
    		});
	        $('#container').highcharts({
	            chart: {
	                type: 'column',
	                marginRight: 130,
	                marginBottom: 25
	            },
	            title: {
	                text: 'Proyectos por Anio',
	                x: -20 //center
	            },
	            /*subtitle: {
	                text: 'Source: WorldClimate.com',
	                x: -20
	            },*/
	            xAxis: {
	                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
	                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
	            },
	            yAxis: {
	                title: {
	                    text: 'Temperature (°C)'
	                },
	                plotLines: [{
	                    value: 0,
	                    width: 1,
	                    color: '#808080'
	                }]
	            },
	            tooltip: {
	                valueSuffix: '°C'
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
  <?php include("include/menu-al.inc"); ?>
  <header></header>
  <section>
  	<input type="button" id="btn_genera" onclick="javascript: cargar()" value="Ver gráfico" />
	<br/>
	<div id="container" style="width:100%; height:400px; border-style: solid;"></div>
  </section>
  <div id="space"></div>
  <footer></footer>
</body>
</html>