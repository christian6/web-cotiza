<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Proyecto por Año</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script>
		//google.load('visualization',1,(package:['corechart']));
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart () {
			var djson = $.ajax({
				data: {'tra':'col'},
				type : 'POST',
				url : 'includes/incproxanio.php',
				dataType : 'JSON',
				async : false
			}).responseText;
			//alert(djson);
			//djson = eval( "("+ djson +")");
			djson = JSON.parse(djson);
			///alert(djson);
			//console.log(djson);
			var data = google.visualization.arrayToDataTable(djson);
			var options = {
				title : 'Cantidad de Proyectos por Años',
				height : 400,
				width : 650,
				titleTextStyle : {color : 'black',fontName : 'arial',fontSize:18, bold: true, italic: true },
				legend: {position : 'right', alignment: 'end'},
			}
			var chart = new google.visualization.ColumnChart(document.getElementById('cont'));
			chart.draw(data,options);
		}
		//google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart2);
		function drawChart2 () {
			var djson = $.ajax({
				data: {'tra':'line'},
				url : 'includes/incproxanio.php',
				type : 'POST',
				dataType : 'JSON',
				async : false
			}).responseText;
			console.log(djson);
			djson = JSON.parse(djson);
			console.log(djson);
			var data = google.visualization.arrayToDataTable(djson);
			var options = {
				title : 'Cantidad de Proyectos por Años',
				//titlePosition : 'out',
				height : 400,
				width : 650,
				titleTextStyle : {color : 'black',fontName : 'arial',fontSize:18, bold: true, italic: true },
				//backgroundColor : '#FFFFAA',
				//backgroundColor : { stroke : '#FF0000',strokeWidth : 2},
				fontSize : 16,
				//hAxis : {title: 'Hola', titleTextStyle: {color: '#FF0000'}},
				//hAxis : {gridlines : {color: '#333', count: 4}},
				legend: {position : 'right', alignment: 'end'},
				//tooltip : { textStyle : '#2B0000', showcolorcode: true}
			}
			var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
			chart.draw(data,options);
		}
	</script>
	<style>
		#tent{
			display: inline-block;
			text-align: center;
			width: 100%;
		}
		#cont, #chart_div{
			margin-left: 25%;
		}
	</style>
</head>
<body>
	<header></header>
	<?php include ("includes/menu-manager.inc"); ?>
	<section>
		<div class="container well">
			<div id="tent">
				<div id="cont"></div>
				<div id="chart_div"></div>	
			</div>
		</div>
	</section>
	<div id="space"></div>
	<footer></footer>
</body>
</html>