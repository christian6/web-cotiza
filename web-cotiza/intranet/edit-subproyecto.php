<?php
if (isset($_REQUEST['cod']) && isset($_REQUEST['sub'])) {
include ("../datos/postgresHelper.php");

if (isset($_POST['send'])) {
	$cn = new PostgreSQL();
	$query = $cn->consulta("
		UPDATE ventas.subproyectos SET subproyecto = '".$_POST['txtnom']."',fecent = '".$_POST['txtfec']."',
		obser = '".$_POST['txtobser']."',esid = '".$_POST['cboestado']."'
		WHERE proyectoid LIKE '".$_POST['cod']."' AND  subproyectoid LIKE '".$_POST['sub']."'
		");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "
		<div id='msg-s' class='alert alert-success'>
			<b>Bien hecho!</b> Se Edito Correctamente! Pro: ".$_POST['cod']." Sub: ".$_POST['sub']."
			<button class='btn btn-success' onClick='javascript:self.window.close();' >Salir</button>
		</div>
		";
}

?>
<html>
<head>
	<title>Editar Subproyecto Nro: <?php echo $_REQUEST['cod']?></title>
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
		<h5>Editar Proyecto Nro: <?php echo $_REQUEST['cod'];?> y Sub-proyecto Nro <?php echo $_REQUEST['sub'];?></h5>
	</div>
	<div class="row well">
<?php

	$cn = new PostgreSQL();
	$query = $cn->consulta("
				SELECT p.proyectoid,p.descripcion,s.subproyectoid,s.subproyecto,s.fecha::date,s.fecent,s.obser,e.esnom
				FROM ventas.proyectos p INNER JOIN ventas.subproyectos s
				ON p.proyectoid = s.proyectoid
				INNER JOIN admin.estadoes e
				ON s.esid = e.esid
				WHERE s.esid = '26' AND p.esid = '17' AND s.proyectoid LIKE '".$_REQUEST['cod']."' AND s.subproyectoid LIKE '".$_REQUEST['sub']."'
				GROUP BY p.proyectoid,p.descripcion,s.subproyectoid,s.subproyecto,s.fecha,s.fecent,s.obser,e.esnom
				ORDER BY p.proyectoid ASC
		");
	if ($cn->num_rows($query)>0){
		$res = $cn->ExecuteNomQuery($query);
?>
		<form name="frmedit" class="well form-block" method="POST" action="">
			<div class="row">
				<input type="hidden" name="cod" value="<?echo $_REQUEST['cod']?>">
				<input type="hidden" name="sub" value="<?echo $_REQUEST['sub']?>">
				<label for="lblsub">Descripcion:</label>
				<input type="text" name="txtnom" class="span6" value="<?php echo $res['subproyecto']?>" title="Nombre de Proyecto" placeholder="Ingrese Nombre de Proyecto" REQUIRED>
				<label for="lblfec">Fecha Entrega:</label>
				<input type="text" id="txtfec" name="txtfec" class="span3" value="<?php echo $res['fecent']?>" title="Fecha de Entrega" placeholder="aaaa-mm-dd" REQUIRED>
				<label for="lblest">Estado:</label>
				<select name="cboestado" class="span3">
					<?php 
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT esid,esnom FROM admin.estadoes WHERE estid LIKE '14'");
						while ($result = $cn->ExecuteNomQuery($query)) {
							if ($res['esnom'] == $result['esnom']) {
								echo "<option value='".$result['esid']."' SELECTED>".$result['esnom']."</option>";
							}else{
								echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
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
	<br><br>
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