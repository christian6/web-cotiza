<?php
if(isset($_REQUEST['cod'])){
include ("../datos/postgresHelper.php");

if (isset($_GET['send'])) {
	$cn = new PostgreSQL();
	$query = $cn->consulta("
		UPDATE ventas.proyectos SET descripcion = '".$_GET['txtnom']."', fecent = '".$_GET['txtfec']."',ruccliente = '".$_GET['cbocliente']."',
		direccion = '".$_GET['txtdir']."', paisid = '".$_GET['cbopais']."', departamentoid = '".$_GET['cbodepartamento']."', provinciaid = '".$_GET['cboprovincia']."',
		distritoid = '".$_GET['cbodistrito']."', obser = '".$_GET['txtobser']."'
		WHERE proyectoid = '".$_GET['cod']."'
		");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "
		<div id='msg-s' class='alert alert-success'>
			<b>Bien hecho!</b> Se Edito Correctamente! ".$_REQUEST['cod']."
			<button class='btn btn-success' onClick='javascript:self.window.close();' >Salir</button>
		</div>
		";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Editar Proyecto <?php echo $_REQUEST['cod'] ?></title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
	<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
  	<script>
	  $(function() {
	    $( "#txtfec" ).datepicker({ minDate: "", maxDate: "" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "yy-mm-dd" });
	  });
	  </script>
	<style>
		.well{  margin: 0 auto; padding: 0 0 0 2em; }
		.ui-widget
		{
			font-size: 10px;
			margin: 0 auto;
		}
	</style>
</head>
<body>
<div class="row-fluid show-grid">
	<div class="well">
		<h5>Editar Proyecto Nro: <?php echo $_REQUEST['cod'];?></h5>
	</div>
	<div class="row well">
<?php

	$cn = new PostgreSQL();
	$query = $cn->consulta("
				SELECT p.proyectoid,p.descripcion,c.nombre,p.fecha::date,p.fecent,p.direccion,p.paisid,a.paisnom,p.departamentoid,d.deparnom,p.provinciaid,r.provnom,p.distritoid,i.distnom,p.obser,e.esnom FROM ".
				"ventas.proyectos p INNER JOIN admin.pais a ".
				"ON p.paisid=a.paisid ".
				"INNER JOIN admin.departamento d ".
				"ON p.departamentoid=d.departamentoid ".
				"INNER JOIN admin.provincia r ".
				"ON p.provinciaid=r.provinciaid ".
				"INNER JOIN admin.distrito i ".
				"ON p.distritoid=i.distritoid ".
				"INNER JOIN admin.estadoes e ".
				"ON p.esid=e.esid ".
				"INNER JOIN admin.clientes c ".
				"ON p.ruccliente=c.ruccliente ".
				"WHERE a.paisid LIKE d.paisid AND d.departamentoid LIKE r.departamentoid AND r.provinciaid LIKE i.provinciaid AND p.esid LIKE '17' AND p.proyectoid LIKE '".$_REQUEST['cod']."'
				 ORDER BY p.proyectoid ASC
		");
	if ($cn->num_rows($query)>0){
		$res = $cn->ExecuteNomQuery($query);
?>
		<form name="frmedit" class="well form-block" method="GET" action="">
			<div class="row">
				<input type="hidden" name="cod" value="<?echo $_REQUEST['cod']?>">
				<label for="lblrz">Nombre Proyecto:</label>
				<input type="text" name="txtnom" class="span6" value="<?php echo $res['descripcion']?>" title="Nombre de Proyecto" placeholder="Ingrese Nombre de Proyecto" REQUIRED>
				<label for="lblfec">Fecha Entrega:</label>
				<input type="text" id="txtfec" name="txtfec" class="span3" value="<?php echo $res['fecent']?>" title="Fecha de Entrega" placeholder="aaaa-mm-dd" REQUIRED>
				<label for="lbldir">Direccion:</label>
				<input type="text" name="txtdir" class="span6" value="<?php echo $res['direccion']?>" placeholder="Ingrese Direccion" REQUIRED>
				<label for="lblpais">Pais:</label>
				<select name="cbopais" class="span3" onChange="javascript:document.frmedit.submit();" REQUIRED>
					<?php
						$c = new PostgreSQL();
						$q = $cn->consulta("SELECT paisid,paisnom FROM admin.pais");
						while ($r = $c->ExecuteNomQuery($q)) {
							if(isset($_GET['cbopais'])){
								if ($_GET['cbopais'] == $r['paisid']) {
									echo "<option value='".$r['paisid']."' SELECTED>".$r['paisnom']."</option>";
								}else{
									echo "<option value='".$r['paisid']."'>".$r['paisnom']."</option>";
								}
							}else{
								if ($res['paisnom'] == $r['paisnom']) {
									echo "<option value='".$r['paisid']."' SELECTED>".$r['paisnom']."</option>";
								}else{
									echo "<option value='".$r['paisid']."'>".$r['paisnom']."</option>";
								}
							}
						}
						$c->close($q);
					?>
				</select>
				<label for="lbldep">Departamento:</label>
				<select name="cbodepartamento" class="span3" onChange="javascript:document.frmedit.submit();" REQUIRED>
					<?php
						$c = new PostgreSQL();
						if(isset($_GET['cbopais'])){
							$q = $cn->consulta("SELECT departamentoid,deparnom FROM admin.departamento WHERE paisid LIKE '".$_GET['cbopais']."'");
						}else{
							$q = $cn->consulta("SELECT departamentoid,deparnom FROM admin.departamento WHERE paisid LIKE '".$res['paisid']."'");
						}
						while ($r = $c->ExecuteNomQuery($q)) {
							if (isset($_GET['cbodepartamento'])){
								if ($_GET['cbodepartamento'] == $r['departamentoid']) {
									echo "<option value='".$r['departamentoid']."' SELECTED>".$r['deparnom']."</option>";
								}else{
									echo "<option value='".$r['departamentoid']."'>".$r['deparnom']."</option>";
								}
							}else{
								if ($res['deparnom'] == $r['deparnom']) {
									echo "<option value='".$r['departamentoid']."' SELECTED>".$r['deparnom']."</option>";
								}else{
									echo "<option value='".$r['departamentoid']."'>".$r['deparnom']."</option>";
								}
							}
						}
						$c->close($q);
					?>
				</select>
				<label for="lblpro">Provincia:</label>
				<select name="cboprovincia" class="span3" onChange="javascript:document.frmedit.submit();" REQUIRED>
					<?php
						$c = new PostgreSQL();
						if (isset($_GET['cbopais']) && isset($_GET['cbodepartamento'])){
							$q = $cn->consulta("SELECT provinciaid,provnom FROM admin.provincia WHERE paisid LIKE '".$_GET['cbopais']."' AND departamentoid LIKE '".$_GET['cbodepartamento']."'");
						}else{
							$q = $cn->consulta("SELECT provinciaid,provnom FROM admin.provincia WHERE paisid LIKE '".$res['paisid']."' AND departamentoid LIKE '".$res['departamentoid']."'");
						}
						while ($r = $c->ExecuteNomQuery($q)) {
							if (isset($_GET['cboprovincia'])){
								if ($_GET['cboprovincia'] == $r['provinciaid']) {
									echo "<option value='".$r['provinciaid']."' SELECTED>".$r['provnom']."</option>";
								}else{
									echo "<option value='".$r['provinciaid']."'>".$r['provnom']."</option>";
								}
							}else{
								if ($res['provnom'] == $r['provnom']) {
									echo "<option value='".$r['provinciaid']."' SELECTED>".$r['provnom']."</option>";
								}else{
									echo "<option value='".$r['provinciaid']."'>".$r['provnom']."</option>";
								}
							}
						}
						$c->close($q);
					?>
				</select>
				<label for="lbldis">Distrito:</label>
				<select name="cbodistrito" class="span3" REQUIRED>
					<?php
						$c = new PostgreSQL();
						if (isset($_GET['cbopais']) && isset($_GET['cbodepartamento']) && isset($_GET['cboprovincia'])){
							$q = $cn->consulta("SELECT distritoid,distnom FROM admin.distrito WHERE paisid LIKE '".$_GET['cbopais']."' AND departamentoid LIKE '".$_GET['cbodepartamento']."' AND provinciaid LIKE '".$_GET['cboprovincia']."'");
						}else{
							$q = $cn->consulta("SELECT distritoid,distnom FROM admin.distrito WHERE paisid LIKE '".$res['paisid']."' AND departamentoid LIKE '".$res['departamentoid']."' AND  provinciaid LIKE '".$res['provinciaid']."'");
						}
						while ($r = $c->ExecuteNomQuery($q)) {
							if (isset($_GET['cbodistrito'])){
								if ($_GET['cbodistrito'] == $r['distritoid']) {
									echo "<option value='".$r['distritoid']."' SELECTED>".$r['distnom']."</option>";
								}else{
									echo "<option value='".$r['distritoid']."'>".$r['distnom']."</option>";
								}
							}else{
								if ($res['distnom'] == $r['distnom']) {
									echo "<option value='".$r['distritoid']."' SELECTED>".$r['distnom']."</option>";
								}else{
									echo "<option value='".$r['distritoid']."'>".$r['distnom']."</option>";
								}
							}
						}
						$c->close($q);
					?>
				</select>
				<label for="lblcli">Cliente:</label>
				<select name="cbocliente" class="span3">
					<?php 
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT ruccliente,nombre FROM admin.clientes WHERE esid LIKE '41'");
						while ($result = $cn->ExecuteNomQuery($query)) {
							if ($res['nombre'] == $result['nombre']) {
								echo "<option value='".$result['ruccliente']."' SELECTED>".$result['nombre']."</option>";
							}else{
								echo "<option value='".$result['ruccliente']."'>".$result['nombre']."</option>";
							}
						}
					?>
				</select>
				<label for="lblobs">Observacion:</label>
				<textarea name="txtobser" class="span6" title="Ingrese Observacion" placeholder="Escriba aqui su observacion"><?php echo $res['obser'];?></textarea>
				<p>
					<button type="Submit" name="send" value="send" class="btn btn-primary"> <i class="icon-pencil"></i> Editar </button>
					<button type="Button" class="btn" onClick="javascript:self.window.close();"> <i class="icon-resize-small"></i> Salir</button>
				</p>
			</div>
		</form>
<?php
		}
		$cn->close($query);
?>
	</div>
</div>
</body>
</html>
<?php
}else{
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
	echo "<button type='Button' onClick='javascript:self.window.close();'>Cerrar</button>";
}
?>