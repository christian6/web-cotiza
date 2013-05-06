<!DOCTYPE html>
<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(0);
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
	<script type="text/javascript" src="js/existencia-all.js"></script>
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
        	$( "#txtfecreq" ).datepicker({ minDate: 0, maxDate: "+1M +10D" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "dd/mm/yy" });
   		 	$( ".dropdown-toggle ").dropdown();
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
	<fieldset id="frmd">
		<legend>
			Datos Generales
			<a id="cl" class="close"><i class="icon-minus"></i></a>
		</legend>
		<form action="" method="POST" name="frmcon">
		<label for="lblalm">Almacen:</label>
		<select id="cboal" class="span2" name="cboal">
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
		<label for="lblest">Material:</label>
		<input type="search" id="txtmat" class="span2" name="txtmat" placeholder="Descripcion Material" title="Ingrese parte o el Nombre Completo del material">
		<button id="btncon" class="btn btn-success" type="Submit"><i class="icon-search"></i></button>
		</form>
		<hr />
		<h6>Generar Orden de Suministra para el Almacen</h6>
		<button type="Button" class="btn btn-primary" onclick="os('o');">Orden de Suminstro <br> <img src="../resource/ter32.png"></button>
	</fieldset>
	<div class="container well">
	<h3>Revision de Existencia Fisica de Materiales</h3>
	<hr>
	<?php if(isset($_POST['cboal']) && isset($_POST['txtmat'])){?>
	<table id="tbldet">
		<caption>
			<div class="controls">
				<label class="radio inline"><input type="radio" id="rbtnall" name="rbtnall" title="Seleccionar Todo" onChange="chkall();" /> Seleccionar Todo</label>
				<label class="radio inline"><input type="radio" id="rbtnclear" name="rbtnall" title="Limpiar Todo" onChange="chkall();"/> Limpiar Todo</label>
			</div>
		</caption>
				<thead>
					<th>Chk</th>
					<th>Item</th>
					<th>Codigo</th>
					<th>Nombre</th>
					<th>Medida</th>
					<th>Undidad</th>
					<th>Marca</th>
					<th>Acabado</th>
					<th>Stock Minimo</th>
					<th>Stock Actual</th>
				</thead>
				<tbody>
					<?php
						$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT * FROM almacen.sp_consultarexistenciaall('".$_POST['cboal']."','".$_POST['txtmat']."')");
						if ($cn->num_rows($query)>0) {
							$i = 1;
							while ($result = $cn->ExecuteNomQuery($query)) {
								if ($result['stock'] < $result['stockmin']) {
									echo "<tr class='nothing'>";
									echo "<td><input type='checkbox' id='".$result['materialesid']."' name='matid' CHECKED></td>";
								}else{
									echo "<tr>";
									echo "<td><input type='checkbox' id='".$result['materialesid']."' name='matid'></td>";
								}
								echo "<td style='text-align:center;'>".$i++."</td>";
								echo "<td>".$result['materialesid']."</td>";
								echo "<td>".$result['matnom']."</td>";
								echo "<td>".$result['matmed']."</td>";
								echo "<td style='text-align:center;'>".$result['matund']."</td>";
								echo "<td>".$result['matmar']."</td>";
								echo "<td>".$result['matacb']."</td>";
								echo "<td style='text-align:center;'>".$result['stockmin']."</td>";
								echo "<td style='text-align:center;'>".$result['stock']."</td>";
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
			</div>
			<?php }?>
</section>
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
			<input type="hidden" id="alid" value="<?php echo $_POST['cboal']?>">
			<input type="hidden" id="esid" value="<?php echo $_POST['cboes']?>">
			</td>
		</tr>
		<tr>
			<td>
				<label for="lblemp">Empleado:</label>
			</td>
			<td>
				<input type="text" id="txtempid" name="txtempid" title="Codigo de Personal" value="<?php echo $_SESSION['dni-icr'];?>" DISABLED />
				<input type="text" id="txtempnom" name="txtempnom" title="Nombre de Personl" value="<?php echo $_SESSION['nom-icr'];?>" DISABLED />
			</td>
		</tr>
		<tr>
			<td><label for="lblfec">Fecha Requerida:</label></td>
			<td><input type="text" id="txtfecreq" name="txtfecreq" title="Fecha Requerida por el Almacen" placeholder="dd-mm-yyyy" onChange="valfec(this.value);"/> </td>
		</tr>
		<tr>
			<td><button type="Button" id="btncancelar" onclick="os('c');"> <img src="../resource/cancelar32.png" /> </button></td>
			<td><button type="Button" id="btnnext" onclick="openRequestedPopup();" DISABLED > <img src="../resource/derecho32.png" /> </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="Button" id="btnsalir" style="display: none;" onclick="javscript:location.href=''"> <img src="../resource/salir32.png" /> </button></td>

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