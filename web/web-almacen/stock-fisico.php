<!doctype html>
<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(0);
	}

include ("../datos/postgresHelper.php");
?>
<html lang="es_ES">
<head>
	<meta charset="UTF-8">
	<title>Stock Fisico</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../modules/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="../modules/jquery1.9.js"></script>
	<script src="../modules/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/osum.js"></script>
</head>
<body>
<?php include("../includes/analitycs.inc"); ?>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<section>
<?php
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM almacen.tmpsuministro WHERE empdni LIKE '".$_SESSION['dni-icr']."'");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		if ($result[0] > 0) {
			?>
			<div class="alert alert-block alert-info fade in" style="width: 50em; margin-left: auto; margin-right: auto;">
				<a href="#" class="close" data-dismiss="alert">x</a>
				<h4 class="alert-heading">¡Atención! Esta alerta necesita tu atención.</h4>
				<p>
					Has estado realizando una orden de suministro al parecer no lo has terminado,
					deseas continuar con el?
				</p>
				<p>
					<button class="btn btn-info" onClick="toclose('s');">Si, seguir realizando</button>
					<button class="btn" onClick="toclose('n');">No, Eliminar</button>
				</p>
			</div>
			<?php
		}
	}
	$cn->close($query);
?>
	<div class="container well">
		<h4>Stock de Almacen</h4>
		<form name="frm1" method="POST" action="">
			<div class="row show-grid">
				<div class="span9">
					<div class="row show-grid">
						<div class="span2">
							<label for="lblal"></b>Seleccione Almacen:</b></label>
						</div>
						<div class="span3">
							<select name="cboal" id="cboal" class="span2">
								<?php
									$cn = new PostgreSQL();
									$query = $cn->consulta("SELECT almacenid,descri FROM admin.almacenes WHERE esid LIKE '21'");
									if ($cn->num_rows($query) > 0) {
										while ($result = $cn->ExecuteNomQuery($query)) {
											if ($_POST['cboal'] == $result['almacenid']) {
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
					</div>
					<div class="row show-grid">
						<div class="span2">
							<div class="controls">
								<label class="checkbox"><input type="checkbox" name="chkf" value="f">Stock Faltante.</label>
							</div>
						</div>
						<div class="span1">
							<div class="controls">
								<label class="checkbox"><input type="checkbox" name="chkm" value="m">Minimo:</label>
							</div>
						</div>
						<div class="span1">
							<input type="text" name="txtmin" title="Ingrese Stock Minimo" class="span1">
						</div>
						<div class="span1">
							<div class="controls">
								<label class="checkbox"><input type="checkbox" name="chkn" id="chkn" value="d">Descripción:</label>
							</div>
						</div>
						<div class="span3">
							<input type="text" name="txtdes" class="span4" placeholder="Ingrese descripción">
						</div>
					</div>
					<div class="row show-grid">
						<div class="span2">
							<div class="controls">
								<button type="Submit" class="btn btn-primary" name="btns" value="btns"><i class="icon-search icon-white"></i> Buscar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<hr>
		<table class="table table-bordered table-hover table-condensed">
			<caption>
				<div class="control-group">
					<label class="inline pull-left label label-info"><i style="background-color: rgba(254,46,46,1);">&nbsp;&nbsp;&nbsp;&nbsp;</i> Stock Critico</label>
					<label class="inline pull-left label label-info"><i style="background-color: rgba(250,172,88,1);">&nbsp;&nbsp;&nbsp;&nbsp;</i> Stock menos del Minimo</label>
					<label class="inline pull-left label label-info"><i style="background-color: rgba(255,255,255,1);">&nbsp;&nbsp;&nbsp;&nbsp;</i> Stock Normal</label>
					<button class="btn btn-success pull-right" onClick="openos();"><i class="icon-th-list icon-white"></i> Generar Suministro</button>
					<button class="btn btn-success pull-right" onClick="viewtbl();"><i class="icon-list-alt icon-white"></i> Ver Detalle</button>
				</div>
			</caption>
			<thead>
				<tr>
					<th>Item</th>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Stock Min</th>
					<th>Stock Actual</th>
					<th>Stock Pendiente</th>
					<th>Seleccionar</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if ($_POST['btns'] == "btns") {
						$m = $_POST['txtmin'];
						$d = $_POST['txtdes'];
						$qsql = "SELECT materialesid,matnom,matmed,matund,stockmin,stock,stockpen
								FROM almacen.vw_stock_fisico
								WHERE almacenid LIKE '".$_POST['cboal']."' ";
						# Opciones de Busque
						if ($_POST['chkf']=="f" && $_POST['chkm']=="m" && $_POST['chkn']=="d") {
							$qsql = $qsql." AND stock <= 0 AND stockmin <= ".$_POST['txtmin']." AND lower(matnom) LIKE lower('%".$_POST['txtdes']."%') ORDER BY matnom ASC";
						}elseif ($_POST['chkf']=="f" && $_POST['chkm']=="m" && $_POST['chkn']=="") {
							$qsql = $qsql." AND stock <= 0 AND stockmin <= ".$_POST['txtmin']."  ORDER BY matnom ASC";
						}elseif ($_POST['chkf']=="f" && $_POST['chkm']=="" && $_POST['chkn']=="d") {
							$qsql = $qsql." AND stock <= 0 AND lower(matnom) LIKE lower('%".$_POST['txtdes']."%') ORDER BY matnom ASC";
						}elseif ($_POST['chkf']=="" && $_POST['chkm']=="m" && $_POST['chkn']=="d"){
							$qsql = $qsql." AND stockmin <= ".$_POST['txtmin']." AND lower(matnom) LIKE lower('%".$_POST['txtdes']."%') ORDER BY matnom ASC";
						}elseif ($_POST['chkf']=="f" && $_POST['chkm']=="" && $_POST['chkn']=="") {
							$qsql = $qsql." AND stock <= 0 ORDER BY matnom ASC";
						}elseif ($_POST['chkf']=="" && $_POST['chkm']=="m" && $_POST['chkn']=="") {
							$qsql = $qsql." AND stockmin <= ".$_POST['txtmin']." ORDER BY matnom ASC";
						}elseif ($_POST['chkf']=="" && $_POST['chkm']=="" && $_POST['chkn']=="d") {
							$qsql = $qsql." AND lower(matnom) LIKE lower('%".$_POST['txtdes']."%') ORDER BY matnom ASC";
						}
						$cn = new PostgreSQL();
						$query = $cn->consulta($qsql);
						if ($cn->num_rows($query) > 0) {
							$i = 1;
							while ($result = $cn->ExecuteNomQuery($query)) {
								if($result['stock'] > 0 && $result['stock'] <= $result['stockmin']){
									echo "<tr style='background-color: rgba(250,172,88,1);'>";
								}else if ($result['stock'] <= 0) {
									echo "<tr style='background-color: rgba(254,46,46,1);'>";
								}else{
									echo "<tr>";
								}
								echo "<td style='text-align: center;'>".$i++."</td>";
								echo "<td>".$result['materialesid']."</td>";
								echo "<td>".$result['matnom']."</td>";
								echo "<td>".$result['matmed']."</td>";
								echo "<td style='text-align: center;'>".$result['matund']."</td>";
								echo "<td style='text-align: center;'>".$result['stockmin']."</td>";
								echo "<td style='text-align: center;'>".$result['stock']."</td>";
								echo "<td style='text-align: center;'>".$result['stockpen']."</td>";
								?>
								<td style="text-align: center;">
									<button type="Button" class="btn-warning btn-mini" id="<?php echo $result['materialesid'];?>" onClick="view('<?php echo $result['materialesid'];?>','<?php echo str_replace('"','',$result['matnom']);?>','<?php echo str_replace('"', '', $result['matmed']);?>');">
										<i class="icon-shopping-cart"></i>
									</button></td>
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
					}
				?>
			</tbody>
		</table>
	</div>
	<div id="myModal" class="modal hide fade in">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h4>Agregar Material a Orden de Suministro</h4>
        </div>
        <div class="modal-body">
			<div class="container span4">
					<div class="control-group">
						<label for="lblid">Codigo Material:</label>
						<div class="controls">
							<input type="text" name="txtid" id="txtid" class="span2" DISABLED>
						</div>
					</div>
					<div class="control-group">
						<label for="">Descripción:</label>
						<div class="controls">
							<input type="text" name="txtnom" id="txtnom" class="span3" DISABLED>
						</div>
					</div>
					<div class="control-group">
						<label for="">Medida:</label>
						<div class="controls">
							<input type="text" name="txtmed" id="txtmed" class="span3" DISABLED>
						</div>
					</div>
					<div class="control-group">
						<label for="">Cantidad:</label>
						<div class="controls">
							<input type="number" name="txtcant" id="txtcant" class="input-small">
						</div>
					</div>
			</div>
        </div>
        <div class="modal-footer">
              <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
              <a href="javascript:tmpsum();" class="btn btn-primary"><i class="icon-shopping-cart icon-white"></i> Agregar</a>
        </div>
    </div>
    <div id="modeltbl" class="modal hide fade in">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h4>Detalle de Suministro</h4>
        </div>
        <div class="modal-body">
			<div id="tbl">
			</div>
        </div>
        <div class="modal-footer">
              <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
        </div>
    </div>
    <div id="modelos" class="modal hide fade in">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h4>Orden de Suministro</h4>
        </div>
        <div class="modal-body">
			<div class="container span5">
				<div class="control-group">
					<label for="lblal">Almacen:</label>
					<div class="controls">
						<select name="cboalos" id="cboalos">
							<?php
								$cn = new PostgreSQL();
								$query = $cn->consulta("SELECT almacenid,descri FROM admin.almacenes WHERE esid LIKE '21'");
								if ($cn->num_rows($query) > 0) {
									while ($result = $cn->ExecuteNomQuery($query)) {
										if ($_POST['cboal'] == $result['almacenid']) {
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
				</div>
				<div class="control-group">
					<label for="">Empleado:</label>
					<div class="controls">
						<input type="text" id="txtdni" name="txtdni" class="input-small" value="<?php echo $_SESSION['dni-icr']?>" DISABLED />
						<input type="text" id="txtnombre" name="txtnombre" class="span3" value="<?php echo $_SESSION['nom-icr']?>" DISABLED />
					</div>
				</div>
				<div class="control-group">
					<label for="fec">Fecha Requerida:</label>
					<div class="controls">
						<input type="text" id="txtfec" name="txtfec" class="input-small">
					</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
              <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
              <a href="javascript:genos();" class="btn btn-primary">Generar Suministro</a>
        </div>
    </div>
</section>
<div id="space"></div>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect(0);
}
?>