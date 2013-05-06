<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect();
	}
include ("../datos/postgresHelper.php");
/* Zone of Functions */
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Ver Documentos de Salida</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script>
		$(function() {
			$( ".dropdown-toggle" ).dropdown();
			$( "#fecini" ).datepicker({ minDate: "", maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd" });
			$( "#fecfin" ).datepicker({ minDate: "", maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy/mm/dd" });
		});
		function searchtype () {
			var	n = document.getElementById("btnn");
			var f = document.getElementById("btnf");
			if (n.checked) {
				document.getElementById("fecini").disabled = "disabled";
				document.getElementById("fecfin").disabled = "disabled";
				document.getElementById("txtnro").disabled = "";
			}else if(f.checked){
				document.getElementById("txtnro").disabled = "disabled";
				document.getElementById("fecini").disabled = "";
				document.getElementById("fecfin").disabled = "";
			}
		}
		function anular(t,nrod) {
			if (confirm("Seguro(a) que desea Anular? El documento de Salida\n\r  Nro "+nrod)) {
				xmlhttp = peticion();
				xmlhttp.onreadystatechange=function()
				{
					if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
						if(xmlhttp.responseText=="hecho"){
							document.getElementById("al").style.display = 'block';
							setTimeout(function() {document.location.reload()}, 5000);
						}
					}
				}
		  		var requestUrl;
		  		requestUrl = "include/incmatdoc.php" + "?tipo=a"+"&doc="+encodeURIComponent(t)+"&nro="+encodeURIComponent(nrod);
				xmlhttp.open("POST",requestUrl,true);
				xmlhttp.send();
			}else{
				return;
			}
		}
		function peticion(){
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			return xmlhttp;
		}
	</script>
	<style>
		input[type='radio']
		{
			margin-top: -.2em;
		}
		form .row .span9 .row .span2 label { font-size: 12px; font-weight: bold; }
	</style>
</head>
<body>
<?php include("../includes/analitycs.inc"); ?>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<section>
	<div class="container well">
		<h4>Imprimir Pedidos Atendidos</h4>
		<form class="form" name="frmnf" action ="" method="POST">
			<div class="row show-grid">
				<div class="span9">
					<div class="row show-grid">
						<div class="span2">
							<label><input type="radio" name="btnr" id="btnn" value="n" onChange="searchtype();" /> Nro Documento</label>
						</div>
						<div class="span2">
							<label><input type="radio" name="btnr" id="btnf" value="f" onChange="searchtype();" /> Entre Fechas</label>
						</div>
					</div>
					<div class="row show-grid">
						<div class="span2">
							<label><input type="radio" name="btnrd" value="g" REQUIRED /> Guia de Remision</label>
						</div>
						<div class="span2">
							<label><input type="radio" name="btnrd" value="n" REQUIRED /> Nota de Salida</label>
						</div>
					</div>
					<div class="row show-grid">
						<div class="span3">
							<label>Nro:</label>
							<input type="text" name="txtnro" id="txtnro" class="span2" title="Nro" REQUIRED DISABLED>
						</div>
						<div class="span3">
							<label>Fecha Inicio:</label>
							<input type="text" name="fecini" id="fecini" class="span2" REQUIRED DISABLED>
						</div>
						<div class="span3">
							<label>Fecha Fin:</label>
							<input type="text" name="fecfin" id="fecfin" class="span2" DISABLED>
						</div>
					</div>
					<button type="Submit" class="btn btn-primary" name="btnsearch" value="btnsearch"><i class="icon-search icon-white"></i> Buscar</button>
				</div>
			</div>
		</form>
		<hr>
		<!-- Aqui se Imprime la tabla -->
		<div id="al" class="alert alert-success" style="display: none;">
	        <a class="close" data-dismiss="alert">×</a>
	        <strong>¡Bien hecho!</strong> Has anulado el Documento de Salida.
      	</div>
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th>Item</th>
					<th>Tipo</th>
					<th>Nro Documento</th>
					<th>Nro Pedido</th>
					<th>Nombre Proyecto</th>
					<th>Fecha</th>
					<th>Fecha Traslado</th>
					<th>Con Formato</th>
					<th>Sin Formato</th>
					<th>Anular Doc</th>
				</tr>
			</thead>
			<tbody>
<?php
	if (isset($_POST['btnsearch'])) {
		$p = $_POST['btnrd'];
		$tdoc = "";
		$pri = "";
		if ($_POST['btnrd'] == "g") {
			$qsql = "
				SELECT g.nroguia as nrodoc,g.nropedido,y.descripcion,g.fecha::date,g.fectra as fec
				FROM almacen.guiaremision g INNER JOIN almacen.pedido p
				ON g.nropedido = p.nropedido
				INNER JOIN ventas.proyectos y
				ON p.proyectoid = y.proyectoid
				INNER JOIN admin.estadoes e
				ON g.esid = e.esid AND g.esid LIKE '46'
				";
			$pri = "nroguia";
			$tdoc = "Guia Remision";
		}elseif($_POST['btnrd'] == "n"){
			$qsql = "
				SELECT n.nronsalida as nrodoc,n.nropedido,y.descripcion,n.fecha::date,n.fecsal as fec
				FROM almacen.notasalida n INNER JOIN almacen.pedido p
				ON n.nropedido = p.nropedido
				INNER JOIN ventas.proyectos y
				ON p.proyectoid = y.proyectoid
				INNER JOIN admin.estadoes e
				ON n.esid = e.esid AND n.esid LIKE '01'
				";
			$tdoc = "Nota Salida";
			$pri = "nronsalida";
		}

		if ($_POST['btnr'] == "n") {
				$qsql = $qsql." WHERE ".$p.".$pri LIKE '".$_POST['txtnro']."' ORDER BY ".$p.".$pri ASC";
		}elseif($_POST['btnr'] == "f"){
			if ($_POST['fecini'] != "" && $_POST['fecfin'] == "") {
				$qsql = $qsql." WHERE ".$p.".fecha::date = '".$_POST['fecini']."'::date  ORDER BY ".$p.".$pri ASC";
			}elseif($_POST['fecini'] != "" && $_POST['fecfin'] != ""){
				$qsql = $qsql." WHERE ".$p.".fecha::date BETWEEN '".$_POST['fecini']."'::date AND '".$_POST['fecfin']."'::date  ORDER BY ".$p.".$pri ASC";
			}
		}
		$i = 1;
		$cn = new PostgreSQL();
		$query = $cn->consulta($qsql);
		if ($cn->num_rows($query) > 0) {
			while ($result = $cn->ExecuteNomQuery($query)) {
				echo "<tr>";
				echo "<td style='text-align: center;'>".$i++."</td>";
				echo "<td style='text-align: center;'>".$tdoc."</td>";
				echo "<td>".$result['nrodoc']."</td>";
				echo "<td>".$result['nropedido']."</td>";
				echo "<td>".$result['descripcion']."</td>";
				echo "<td>".$result['fecha']."</td>";
				echo "<td>".$result['fec']."</td>";
				if ($_POST['btnrd'] == "g") {
				?>
					<td style='text-align: center;'><a href="javascript:window.open('http://190.41.246.91/web/reports/almacen/pdf/rptguiaremision?nro=<?php echo $result['nrodoc']?>');"><i class='icon-align-justify'></i></a></td>
					<td style='text-align: center;'><a href="javascript:window.open('http://190.41.246.91/web/reports/almacen/pdf/rptguiaremisionsin?nro=<?php echo $result['nrodoc']?>');"><i class='icon-align-left'></i></a></td>
					<td style="text-align: center;"><a href="javascript:anular('g','<?php echo $result['nrodoc']?>');"><i class="icon-remove-circle"></i></a></td>
				<?php
				}elseif($_POST['btnrd'] == "n") {
				?>
					<td style='text-align: center;'><a href="javascript:window.open('http://190.41.246.91/web/reports/almacen/pdf/rptnotasalida?nro=<?php echo $result['nrodoc']?>');"><i class='icon-align-justify'></i></a></td>
					<td style='text-align: center;'><a href="javascript:window.open('http://190.41.246.91/web/reports/almacen/pdf/rptnotasalidasin?nro=<?php echo $result['nrodoc']?>');"><i class='icon-align-left'></i></a></td>
					<td style='text-align: center;'><a href="javascript:anular('n','<?php echo $result['nrodoc']?>');"><i class="icon-remove-circle"></i></a></td>
				<?php
				}
				echo "</tr>";
			}
		}else{
			echo "<div class='alert alert-warning'>
				<a class='close' data-dismiss='alert'>x</a>
				<b>¡Atención!</b> Esta alerta necesita tu atención, pero no es muy importante.
				<h4>No se encontraron resultados</h4>
				</div>";
		}
	}
?>
			</tbody>
		</table>
	</div>
</section>
<div id="space"></div>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>