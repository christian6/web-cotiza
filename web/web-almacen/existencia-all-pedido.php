<!DOCTYPE html>
<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect(1);
	}
include ("../datos/postgresHelper.php");
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Existencia Fisica</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint-existencia-all.css">
	<script type="text/javascript" src="js/existencia.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
  	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedido.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script>
		$(function() {
			var state = true;
        	$( "#txtfecreq" ).datepicker({ minDate: 0, maxDate: "+1M +10D" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "dd/mm/yy" });
        	$('.dropdown-toggle').dropdown();
    		$( "#frmd" ).draggable();
    		$( "#cl" ).click(function(){
    			if ( state ) {
			        $( "#frmd" ).animate({
			          	left: '-17%'
			        }, 1000);

			    } else {
			        $( "#frmd" ).animate({
			          left: '2%'
			        }, 1000);
			    }
			    state = !state;
			});
		});
	</script>
</head>
<body>
<?php include("../includes/analitycs.inc"); ?>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<section>
<div>
	<fieldset id="frmd">
		<legend>
			Datos Generales
			<a id="cl" class="close"><i class="icon-minus"></i></a>
		</legend>
		<div id="fm">
		<form class="form-horizontal" action="" method="POST" name="frmcon">
		<label for="lblalm" class="label label-inverse">Almacen:</label>
		<select id="cboal" name="cboal" class="span2">
			<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT almacenid,descri FROM admin.almacenes WHERE esid LIKE '21'");
				if ($cn->num_rows($query)>0) {
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
		<label class="label label-inverse" for="lblest">Estado:</label>
		<select id="cboes" name="cboes"  class="span2">
			<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT esid,esnom FROM admin.estadoes WHERE estid LIKE '16' AND esid LIKE '35' OR esid LIKE '37'");
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						if ($result['esid'] == $_POST['cboes']) {
							echo "<option value='".$result['esid']."' SELECTED>".$result['esnom']."</option>";
						}else{
							echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
						}
					}
				}
				$cn->close($query);
			?>
		</select>
		<button id="btncon" class="btn btn-success" type="Submit"><i class="icon-search"></i></button>
		</form>
		<hr />
		<h6>Generar Orden de Suministra para el Almacen</h6>
		<button type="Button" class="btn btn-primary" onclick="os('o');">Orden de Suminstro <br> <img src="../resource/ter32.png"></button>
	</div>
	</fieldset>
</div>
<div class="container well">
<h3>Revision de Existencia Fisica de Materiales de los Pedidos</h3>
	<?php if(isset($_POST['cboal']) && isset($_POST['cboes'])){?>
	<table id="tbldet">
		<caption>
			<div class="controls">
				<label class="radio inline"><input type="radio" id="rbtnall" name="rbtnall" title="Seleccionar Todo" onChange="chkall();" CHECKED /> Seleccionar Todo</label>
				<label class="radio inline"><input type="radio" id="rbtnclear" name="rbtnall" title="Limpiar Todo" onChange="chkall();"  /> Limpiar Todo</label>
			</div>
		</caption>
				<thead>
					<th>Chk</th>
					<th>Item</th>
					<th>Nro Pedido</th>
					<th>Codigo</th>
					<th>Nombre</th>
					<th>Medida</th>
					<th>Undidad</th>
					<th>Cantidad</th>
					<th>Stock Actual</th>
				</thead>
				<tbody>
					<?php
						$cn = new PostgreSQL();
						if($_POST['cboes'] == "35"){
							$query = $cn->consulta("SELECT * FROM almacen.sp_consultarexistenciatodoxestado('".$_POST['cboal']."','".$_POST['cboes']."')");
						}else if($_POST['cboes'] == "37"){
							$query = $cn->consulta("SELECT * FROM almacen.sp_consultarexistenciatodoxincompleto('".$_POST['cboal']."','".$_POST['cboes']."')");
						}
						if ($cn->num_rows($query)>0) {
							$i = 1;
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<tr>";
								echo "<td><input type='checkbox' id='".$result['materialesid']."' name='matid' CHECKED></td>";
								echo "<td style='text-align:center;'>".$i++."</td>";
								echo "<td>".$result['nropedido']."</td>";
								echo "<td>".$result['materialesid']."</td>";
								echo "<td>".$result['matnom']."</td>";
								echo "<td>".$result['matmed']."</td>";
								echo "<td style='text-align:center;'>".$result['matund']."</td>";
								echo "<td style='text-align:center;'>".$result['cantidad']."</td>";
								echo "<td style='text-align:center;'>".$result['existencia']."</td>";
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
			<?php }?>
</section>
</div>
<div id="fullscreem"></div>
<div id="frmos">
	<h4>Orden de Sumistro</h4>
	<hr />
	<table>
		<tr>
			<td>
			<label for="lblala">Almcen:</label></td>
			<td>
			<input type="text" id="txtalid" name="txtalid" title="Codigo de Almacen" value="<?php echo $_POST['cboal'];?>" DISABLED />
			<input type="text" id="txtalnom" name="txtalnom" title="Nombre de Almacen" DISABLED />
			</td>
		</tr>
		<tr>
			<td>
				<label for="lblemp">Empleado:</label>
			</td>
			<td>
				<input type="text" id="txtempid" name="txtempid" title="Codigo de Personal" value="<?php echo $_SESSION['dni'];?>" DISABLED />
				<input type="text" id="txtempnom" name="txtempnom" title="Nombre de Personl" value="<?php echo $_SESSION['nom'];?>" DISABLED />
			</td>
		</tr>
		<tr>
			<td><label for="lblfec">Fecha Requerida:</label></td>
			<td><input type="text" id="txtfecreq" name="txtfecreq" title="Fecha Requerida por el Almacen" placeholder="dd-mm-yyyy"/> </td>
		</tr>
		<tr>
			<td><button type="Button" onclick="os('c');"> <img src="../resource/cancelar32.png" /> </button></td>
			<td><button type="Button" onclick="save();"> <img src="../resource/floppy32.png" /> </button></td>
		</tr>
	</table>
</div>
<div id="generator">
<h4>Orden de Suministro Generado</h4>
<hr />
<h4 id="nrosu"></h4>
<hr />
<button type="Button" onclick="javascript:location.href='?';" title="Salir" > <img src="../resource/inicio32.png" /> </button>
</div>
<div style="height: 70px;"></div>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>