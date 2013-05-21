<?php
if (isset($_REQUEST['cod']) && isset($_REQUEST['sub']) && isset($_REQUEST['nrp'])) {
include ("../datos/postgresHelper.php");

if (isset($_POST['send'])) {
	$cn = new PostgreSQL();
	$query = $cn->consulta("
		UPDATE ventas.sectores SET sector = '".$_POST['txtsec']."',descripcion = '".$_POST['txtdescri']."',
		esid = '".$_POST['cboestado']."'
		WHERE proyectoid LIKE '".$_POST['cod']."' AND  subproyectoid LIKE '".$_POST['sub']."' AND  nroplano LIKE '".$_POST['nrp']."'
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
	<title>Editar Sector Nro: <?php echo $_REQUEST['nrp']?></title>
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
		<h5>Editar Proyecto Nro: <?php echo $_REQUEST['cod'];?> y Sub-proyecto Nro <?php echo $_REQUEST['sub'];?> Nro Plano: <?php echo $_REQUEST['nrp'];?></h5>
	</div>
	<div class="row well">
<?php

	$cn = new PostgreSQL();
	$query = $cn->consulta("
				SELECT s.proyectoid,s.subproyectoid,s.nroplano,s.sector,s.descripcion,t.esnom FROM ventas.sectores s
				INNER JOIN admin.estadoes t
				ON s.esid = t.esid
				WHERE s.esid LIKE '29' AND s.proyectoid LIKE '".$_REQUEST['cod']."' AND s.subproyectoid LIKE '".$_REQUEST['sub']."'
		");
	if ($cn->num_rows($query)>0){
		$res = $cn->ExecuteNomQuery($query);
?>
		<form name="frmedit" class="well form-block" method="POST" action="">
			<div class="row">
				<input type="hidden" name="cod" value="<?echo $_REQUEST['cod']?>">
				<input type="hidden" name="sub" value="<?echo $_REQUEST['sub']?>">
				<input type="hidden" name="nrp" value="<?echo $_REQUEST['nrp']?>">
				<label for="lblsub">Sector:</label>
				<input type="text" name="txtsec" class="span6" value="<?php echo $res['sector']?>" title="Sector" placeholder="Ingrese Sector" REQUIRED>
				<label for="lblobs">Descripcion del Sector:</label>
				<textarea name="txtdescri" class="span6" title="Ingrese Descripcion" placeholder="Escriba aqui su descripcion"><?php echo $res['descripcion'];?></textarea>
				<label for="lblest">Estado:</label>
				<select name="cboestado" class="span3">
					<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT esid,esnom FROM admin.estadoes WHERE estid LIKE '15'");
						while ($result = $cn->ExecuteNomQuery($query)) {
							if ($res['esnom'] == $result['esnom']) {
								echo "<option value='".$result['esid']."' SELECTED>".$result['esnom']."</option>";
							}else{
								echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
							}
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