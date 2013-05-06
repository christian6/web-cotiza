<?php
if(isset($_REQUEST['ruc'])){
include ("../datos/postgresHelper.php");

if (isset($_GET['send'])) {
	$cn = new PostgreSQL();
	$query = $cn->consulta("
		UPDATE admin.proveedor SET razonsocial = '".$_GET['txtrz']."',direccion = '".$_GET['txtdir']."',
		paisid = '".$_GET['cbopais']."', departamentoid = '".$_GET['cbodepartamento']."', provinciaid = '".$_GET['cboprovincia']."',
		distritoid = '".$_GET['cbodistrito']."', telefono = '".$_GET['txttel']."', tipo = '".$_GET['cbotipo']."', origen = '".$_GET['cboorigen']."'
		WHERE rucproveedor = '".$_GET['ruc']."'
		");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "
		<div id='msg-s' class='alert alert-success'>
			<b>Bien hecho!</b> Se Edito Correctamente! ".$_REQUEST['ruc']."
			<button class='btn btn-success' onClick='javascript:self.window.close();' >Salir</button>
		</div>
		";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Editar Proveedor Nro RUC: <?php echo $_REQUEST['ruc'] ?></title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<style>
		.well{  margin: 0 auto; padding: 0 0 0 2em; }
	</style>
</head>
<body>
<div class="row-fluid show-grid">
	<div class="well">
		<h5>Editar Proveedot Nro Ruc: <?php echo $_REQUEST['ruc'];?></h5>
	</div>
	<div class="row well">
<?php

	$cn = new PostgreSQL();
	$query = $cn->consulta("
							SELECT p.rucproveedor,p.razonsocial,p.direccion,p.paisid,a.paisnom,p.departamentoid,d.deparnom,p.provinciaid,r.provnom,p.distritoid,i.distnom,p.telefono,p.tipo,p.origen,e.esnom
						FROM admin.proveedor p INNER JOIN admin.pais a
						ON p.paisid=a.paisid
						INNER JOIN admin.departamento d
						ON p.departamentoid=d.departamentoid
						INNER JOIN admin.provincia r
						ON p.provinciaid=r.provinciaid
						INNER JOIN admin.distrito i
						ON p.distritoid=i.distritoid
						INNER JOIN admin.estadoes e
						ON p.esid=e.esid
						WHERE a.paisid LIKE d.paisid AND d.departamentoid LIKE r.departamentoid AND r.provinciaid LIKE i.provinciaid AND p.rucproveedor LIKE '".$_REQUEST['ruc']."' AND p.esid LIKE '15'
						ORDER BY p.razonsocial ASC
		");
	if ($cn->num_rows($query)>0){
		$res = $cn->ExecuteNomQuery($query);
?>
		<form name="frmedit" class="well form-block" method="GET" action="">
			<div class="row">
				<input type="hidden" name="ruc" value="<?echo $_REQUEST['ruc']?>">
				<label for="lblrz">Razon Social:</label>
				<input type="text" name="txtrz" class="span6" value="<?php echo $res['razonsocial']?>" title="Razon Social" placeholder="Ingrese Razon Social" REQUIRED>
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
				<label for="lbltel">Telefono:</label>
				<input type="text" name="txttel" class="span3" value="<?php echo $res['telefono'];?>" title="Telefono" placeholder="Ingrese Telefono" REQUIRED />
				<label for="lbltipo">Tipo:</label>
				<select name="cbotipo" class="span3" REQUIRED>
					<?php
					if ($res['tipo']=="JURIDICA"){
						echo "<option value='JURIDICA' SELECTED>JURIDICA</td>";
						echo "<option value='NATURAL'>NATURAL</td>";
					}
					if ($res['tipo']=="NATURAL"){
						echo "<option value='JURIDICA'>JURIDICA</td>";
						echo "<option value='NATURAL' SELECTED>NATURAL</td>";
					}
					?>
				</select>
				<label for="lblorigen">Origen:</label>
				<select name="cboorigen" class="span3" REQUIRED>
					<?php
					if ($res['origen']=="NACIONAL"){
						echo "<option value='NACIONAL' SELECTED>NACIONAL</td>";
						echo "<option value='INTERNACIONAL'>INTERNACIONAL</td>";
					}
					if ($res['origen']=="INTERNACIONAL"){
						echo "<option value='NACIONAL'>NACIONAL</td>";
						echo "<option value='INTERNACIONAL' SELECTED>INTERNACIONAL</td>";
					}
					?>
				</select>
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
<footer>
</footer>
</body>
</html>
<?php
}else{
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
	echo "<button type='Button' onClick='javascript:self.window.close();'>Cerrar</button>";
}
?>