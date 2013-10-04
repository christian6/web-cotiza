<!DOCTYPE html>
<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect(0);
	}

include ("../datos/postgresHelper.php");
?>
<html lang="es_ES">
<head>
	<meta charset="utf-8" />
	<title>Aprobar Orden de Suministro</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint-aprobar-suministro.css">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
  	<script type="text/javascript" src="js/aprobar-suministro.js"></script>
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedido.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
  	<script>
		 $(function() {
        	$( "#txtfeci,#txtfecf" ).datepicker({ numberOfMonths: 3, changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "dd/mm/yy" });
        	$( ".dropdown-toggle" ).dropdown();
        	$( "#frmd" ).draggable();
   		 });
	</script>
</head>
<body>
<?php include("../includes/analitycs.inc"); ?>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<section>
	<article id="frmd">
		<legend>Datos Generales</legend>
		<form class="form-horizontal" name="frm" method="POST" action="">
			<div class="control-group">
				<label for="lblal">Almacen:</label>
					<select id="cboal" name="cboal">
					<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT almacenid,descri FROM admin.almacenes WHERE esid LIKE '21'");
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								if ($result['almacenid'] == $_POST['cboal']) {
									echo "<option value='".$result['almacenid']."' SELECTED>".$result['descri']."</option>";
								}else{
									echo "<option value='".$result['almacenid']."'>".$result['descri']."</option>";
								}
							}
						}
						$cn->close($query);
					?>
					</select>
			</div>
			<div class="control-group">
				<label for="lblfeci">Fecha Inicio:</label>
				<input type="text" id="txtfeci" name="txtfeci" title="Ingrese Fecha de Inicio" placeholder="dd/MM/yyyy" />
			</div>
			<div class="control-group">
				<label for="lblfecf">Fecha Fin:</label>
				<input type="text" id="txtfecf" name="txtfecf" title="Ingrese Fecha de Fin" placeholder="dd/MM/yyyy" />
			</div>
			<div class="control-actions">
				<button type="Submit" class="btn" id="btns"><i class="icon-search"></i> Buscar</button>
			</div>
		</form>
	</article>
	<table id="tbldet">
		<thead>
			<tr>
				<th>Nro Suministro</th>
				<th>Almacen</th>
				<th>Empleado</th>
				<th>Fec. Generado</th>
				<th>Fec. Requerido</th>
				<th>Estado</th>
				<th>Revisar</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (isset($_POST['cboal'])) {
				$cn = new PostgreSQL();
				$sql = "";
				if ($_POST['txtfeci'] != "" && $_POST['txtfecf'] != "" && $_POST['cboal'] != "") {
					$sql = "SELECT s.nrosuministro,a.descri,e.empnom,s.fecha::date,s.fecreq,d.esnom FROM almacen.suministro s
							INNER JOIN admin.almacenes a
							ON s.almacenid=a.almacenid
							INNER JOIN admin.empleados e
							ON s.empdni=e.empdni
							INNER JOIN admin.estadoes d
							ON s.esid=d.esid
							WHERE s.esid LIKE '40' AND s.almacenid LIKE '".$_POST['cboal']."' AND  s.fecha::date BETWEEN to_date('".$_POST['txtfeci']."','dd-mm-yyyy') AND to_date('".$_POST['txtfecf']."','dd-mm-yyyy')
							ORDER BY s.nrosuministro ASC";
				}else if($_POST["txtfeci"] != "" && $_POST["txtfecf"] == ""){
					$sql = "SELECT s.nrosuministro,a.descri,e.empnom,s.fecha::date,s.fecreq,d.esnom FROM almacen.suministro s
							INNER JOIN admin.almacenes a
							ON s.almacenid=a.almacenid
							INNER JOIN admin.empleados e
							ON s.empdni=e.empdni
							INNER JOIN admin.estadoes d
							ON s.esid=d.esid
							WHERE s.esid LIKE '40' AND s.almacenid LIKE '".$_POST['cboal']."' AND  s.fecha::date = to_date('".$_POST['txtfeci']."','dd-mm-yyyy')
							ORDER BY s.nrosuministro ASC";
				}else if ($_POST['txtfeci'] == "" && $_POST['txtfecf'] == "" && $_POST['cboal'] != "") {
					$sql = "SELECT s.nrosuministro,a.descri,e.empnom,s.fecha::date,s.fecreq,d.esnom FROM almacen.suministro s
							INNER JOIN admin.almacenes a
							ON s.almacenid=a.almacenid
							INNER JOIN admin.empleados e
							ON s.empdni=e.empdni
							INNER JOIN admin.estadoes d
							ON s.esid=d.esid
							WHERE s.esid LIKE '40' AND s.almacenid LIKE '".$_POST['cboal']."'
							ORDER BY s.nrosuministro ASC";
				}
			}else{
				$sql = "SELECT s.nrosuministro,a.descri,e.empnom,s.fecha::date,s.fecreq,d.esnom FROM almacen.suministro s
						INNER JOIN admin.almacenes a
						ON s.almacenid=a.almacenid
						INNER JOIN admin.empleados e
						ON s.empdni=e.empdni
						INNER JOIN admin.estadoes d
						ON s.esid=d.esid
						WHERE s.esid LIKE '40' AND s.almacenid LIKE '0001'
						ORDER BY s.nrosuministro ASC";
			}
				$query = $cn->consulta($sql);

				if ($cn->num_rows($query) > 0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<tr>";
						echo "<td>".$result['nrosuministro']."</td>";
						echo "<td>".$result['descri']."</td>";
						echo "<td>".$result['empnom']."</td>";
						echo "<td>".$result['fecha']."</td>";
						echo "<td>".$result['fecreq']."</td>";
						echo "<td>".$result['esnom']."</td>";
						?>
						<td><a href="javascript:openwin('<?php echo $result['nrosuministro']?>');"><img id="ver" src="../resource/ver32.png"></a></td>
				<?php  	echo "</tr>";
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
</section>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect(1);
}
?>