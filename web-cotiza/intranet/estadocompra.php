<!DOCTYPE html>
<?php
session_start();
include ("../datos/postgresHelper.php");
?>
<html lng="es">
<head>
	<meta charset="UTF-8">
	<title>Estado de Cotizacion</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="../js/intranet/estadocot.js"></script>
	<style>
		.label-info{  margin-top: -2em; margin-bottom: .5em; padding-bottom: .5em; }
		.ui-widget{ font-size: 11px; margin: 0 auto; }
	</style>
</head>
<body>
<header>
	<hgroup>
		<img src="../source/icrlogo.png">
		<div id="cab">
			<h1>Especialistas en Sistemas Contra Incendios</h1>
		</div>
	</hgroup>
</header>
<div id="sess">
<?php
$nom = $_SESSION['nom-icr'];
$car = $_SESSION['car-icr'];
?>
<p>
<label for="user" style="font-weight: bold;">Cargo:</label>
<?echo $car;?>&nbsp;
<label for="nom" style="font-weight: bold;">Nombre: </label>
<?echo $nom;?>
</p>
<p>
<label style="font-weight: bold;">Dni:</label>
&nbsp;<?echo $_SESSION['dni-icr']?>&nbsp;
<label style="font-weight: bold;">User:</label>
<?echo $_SESSION['user-icr'];?>
<button id="btnclose" class="btn btn-mini btn-primary" onclick="javascript:document.location.href = '../../web/includes/session-destroy.php';"> <i class="icon-lock"></i> Cerrar Session</button>
</p>
</div>
<section>
<?php include("includes/menu.inc");?>
<div class="container well">
	<div class="row-fluid">
		<h4>Orden de Compra</h4>
		<h6>Busqueda de Orden de Compra:</h6>
	</div>
	<form action="" method="POST" name="frmb">
		<div class="row show-grid">
			<div class="span9">
				<div class="controls label label-info">
					<label class="radio inline"><input type="radio" name="rbtn" id="btnn" value="n" onChange="changer();" REQUIRED /> Nro de Compra</label>
					<label class="radio inline"><input type="radio" name="rbtn" id="btnf" value="f" onChange="changer();" REQUIRED /> Entre Fechas</label>
				</div>
				<div class="row show-grid">
					<div class="span3">
						<div class="control-group">
							<label class="label">Nro Orden de Compra:</label>
							<div class="controls">
								<input type="text" class="span2" id="txtnro" name="txtnro" placeholder="Nro de Compra" title="Ingrese el Numero de Cotizacion a Buscar" REQUIRED DISABLED />
							</div>
						</div>
					</div>
					<div class="span3">
						<div class="control-group">
							<label class="label">Fecha Inicio:</label>
							<div class="controls">
								<input type="text" class="span2" id="fini" name="fini" placeholder="aaaa-mm-dd" title="Ingrese Fecha de Inicio" REQUIRED DISABLED />
							</div>
						</div>
					</div>
					<div class="span3">
						<div class="control-group">
							<label class="label">Fecha Fin:</label>
							<div class="controls">
								<input type="text" class="span2" id="ffin" name="ffin" placeholder="aaaa-mm-dd" title="Ingrese Fecha de Fin" DISABLED />
							</div>
						</div>
					</div>
				</div>
				<button class="btn btn-primary" type="Submit" name="btnb" value="btnb"><i class="icon-search icon-white"></i> Buscar</button>
			</div>
		</div>
	</form>
	<hr>
<?php
	if ($_POST['btnb']=="btnb") {
?>
	<div style="display: none;" id="al" class="alert alert-success">
        <a class="close" data-dismiss="alert">×</a>
        <strong>¡Bien hecho!</strong> Has Anulado la Orden de Compra.
        para ver el resultado tienes que actualizar la pagina.
     </div>
	<h6>Detalle</h6>
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Item</th>
				<th>Nro Orden Compra</th>
				<th>Ruc Proveedor</th>
				<th>Razon Social</th>
				<th>Fecha Realizada</th>
				<th>Fecha Requerido</th>
				<th>Estado</th>
				<th>Ver</th>
				<th>Anular</th>
			</tr>
		</thead>
		<tbody>
<?php
	$cn = new PostgreSQL();
	$qsql = "SELECT c.nrocompra,c.rucproveedor,r.razonsocial,c.fecha::date,c.fecent::date,e.esnom
							FROM logistica.compras c INNER JOIN admin.proveedor r
							ON c.rucproveedor=r.rucproveedor
							INNER JOIN admin.estadoes e
							ON c.esid=e.esid AND c.esid = '12'
							WHERE";
	if ($_POST['rbtn'] == "n") {
		$qsql = $qsql." c.nrocompra LIKE '".$_POST['txtnro']."'";
	}else if($_POST['rbtn'] == "f"){
		if ($_POST['fini']!="" && $_POST['ffin'] == "") {
			$qsql = $qsql." c.fecha::date = '".$_POST['fini']."'::date ORDER BY c.nrocompra ASC";
		}else if($_POST['fini']!="" && $_POST['ffin'] != ""){
			$qsql = $qsql." c.fecha::date BETWEEN '".$_POST['fini']."'::date AND '".$_POST['ffin']."'::date ORDER BY c.nrocompra ASC";
		}
	}
	$query = $cn->consulta($qsql);
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr>";
			echo "<td style='text-align: center;'>".$i++."</td>";
			echo "<td>".$result['nrocompra']."</td>";
			echo "<td>".$result['rucproveedor']."</td>";
			echo "<td>".$result['razonsocial']."</td>";
			echo "<td style='text-align: center;'>".$result['fecha']."</td>";
			echo "<td style='text-align: center;'>".$result['fecent']."</td>";
			echo "<td style='text-align: center;'>".$result['esnom']."</td>";
			?>
			<td style='text-align: center;'><a href="javascript:viewcompra('<?php echo $result['nrocompra'];?>','<?php echo $result['rucproveedor'];?>');"><i class='icon-eye-open'></i></a></td>
			<td style='text-align: center;'><a href="javascript:anularcompra('<?php echo $result['nrocompra'];?>')"><i class='icon-remove-circle'></i></a></td>
			<?php
			echo "</tr>";
		}
	}else{
		echo "<div class='alert alert-warning'>
				<a class='close' data-dismiss='alert'>x</a>
				<b>¡Atención!</b> Esta alerta necesita tu atención, pero no es muy importante.
				<h4>No se encontraron resultados</h4>
				</div>";
	}
	$cn->close($query);
?>
		</tbody>
	</table>
<?php
	}
?>
</div>
</section>
<div id="space"></div>
<footer>
</footer>
</body>
</html>