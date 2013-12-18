<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
  if (sestrust('k') == 0) {
    redirect();
  }
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Clientes</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="../css/styleint.css">
  	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <script src="../modules/msgBox.js"></script>
    <link rel="stylesheet" href="../css/msgBoxLight.css">
		<script src="js/customers.js"></script>
</head>
<body>
	<?php include ("includes/menu-ventas.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<div class="">
				<h3 class="t-info"><em>Mantenimiento de Clientes</em></h3>
			</div>
				<div class="row show-grid">
					<div class="span3">
						<ul id="tab" class="nav nav-tabs nav-stacked nav-pills">
							<li><a data-toggle="tab" href="#list"> Catalogo</a></li>
							<li><a data-toggle="tab" href="#upkeep"> Mantenimiento</a></li>
						</ul>
					</div>
					<div class="span9">
						<div class="well">
							<div class="tab-content">
								<div class="tab-pane fade in" id="list">
									<h4><em>Catalogo de Clientes</em></h4>
									<table class="table table-striped table-hover table-condensed">
										<thead>
											<tr>
												<th></th>
												<th>Ruc</th>
												<th>Razón Social</th>
												<th>Dirección</th>
												<th>Telefono</th>
												<th>Editar</th>
												<th>Eliminar</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT * FROM admin.clientes WHERE esid LIKE '41' ORDER BY nombre ASC");
												if ($cn->num_rows($query) > 0) {
													$i = 1;
													while ($result = $cn->ExecuteNomQuery($query)) {
														echo "<tr>";
														echo "<td id='tc'>".$i++."</td>";
														echo "<td>".$result['ruccliente']."</td>";
														echo "<td>".$result['nombre']."</td>";
														echo "<td>".$result['direccion']."</td>";
														echo "<td>".$result['telefono']."</td>";
														echo "<td id='tc'>";
														?>
														<button title='Editar Cliente' class='btn btn-mini btn-warning' type='button' onClick="upkeep('<?php echo $result['ruccliente'];?>','<?php echo $result['nombre']; ?>','<?php echo $result['abre'];?>','<?php echo $result['direccion']; ?>','<?php echo $result['telefono']; ?>','<?php echo $result['contacto']; ?>','<?php echo $result['paisid']; ?>','<?php echo $result['departamentoid']; ?>','<?php echo $result['provinciaid']; ?>','<?php echo $result['distritoid']; ?>');">
														<i class='icon-edit'></i>
														</button></td>
														<td id="tc">
															<button type="Button" onClick="deleteCus('<?php echo $result['ruccliente']; ?>');" class="btn btn-mini btn-danger">
																<i class="icon-remove"></i>
															</button>
														</td>
														<?php
														echo "</tr>";
													}
												}else{
													?>
													<div class="alert alert-warning alert-block">
														<strong>No se encontraron datos.</strong>
													</div>
													<?php
												}
												$cn->close($query);
											?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade in" id="upkeep">
									<h4><em>Mantenimiento de Clientes</em></h4>
									<form action="" name="frmcus" method="POST">
										<input type="hidden" id="edit" name="edit" value="<?php echo $_POST['edit']; ?>">
									<div class="row show-grid">
										<div class="span2">
											<div class="control-group">
												<label class="control-label">Ruc Cliente</label>
												<div class="controls">
													<input type="text" class="span2" id="ruc" name="ruc" value="<?php echo $_POST['ruc']; ?>" maxlength="11" title="Ruc de Cliente">
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Razón Social</label>
												<div class="controls">
													<input type="text"  class="span4" id="nom" name="nom" value="<?php echo $_POST['nom']; ?>" title="Nombre o Razoón Social">
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group">
												<label class="control-label">Abreviatura</label>
												<div class="controls">
													<input type="text" class="span2" name="abre" id="abre" value="<?php echo $_POST['abre']; ?>" title="Abreviatura del Cliente">
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="control-group">
												<label class="control-label">Dirección</label>
												<div class="controls">
													<input type="text" class="span4" id="dir" value="<?php echo $_POST['dir']; ?>" name="dir" title="Dirección de Cliente">
												</div>
											</div>
										</div>
										<div class="span2">
											<div class="control-group">
												<label class="control-label">Pais</label>
												<div class="controls">
													<select name="pais" class="span2" id="pais" title="Pais" onChange="changeCombo();">
														<?php
															$cn = new PostgreSQL();
															$query = $cn->consulta("SELECT paisid,paisnom FROM admin.pais ORDER BY paisnom ASC;");
															if ($cn->num_rows($query) > 0) {
																while ($result = $cn->ExecuteNomQuery($query)) {
																	if ($_POST['pais'] == $result['paisid']) {
																		echo "<option value='".$result['paisid']."' SELECTED>".$result['paisnom']."</option>";
																	}else{
																		echo "<option value='".$result['paisid']."'>".$result['paisnom']."</option>";
																	}
																}
															}
															$cn->close($query);
														?>
													</select>
												</div>
											</div>	
										</div>
										<div class="span2">
											<div class="control-group">
												<label class="control-label">Departamento</label>
												<div class="controls">
													<select name="dep" id="dep" class="span2" title="Departamento" onChange="changeCombo();">
														<?php
														$cn = new PostgreSQL();
														$query = $cn->consulta("SELECT departamentoid,deparnom FROM admin.departamento WHERE paisid LIKE '".$_POST['pais']."'");
														if ($cn->num_rows($query) > 0) {
															while ($result = $cn->ExecuteNomQuery($query)) {
																if ($_POST['dep'] == $result['departamentoid']) {
																	echo "<option value='".$result['departamentoid']."' SELECTED>".$result['deparnom']."</option>";
																}else{
																	echo "<option value='".$result['departamentoid']."'>".$result['deparnom']."</option>";
																}
															}
														}
														$cn->close($query);
														?>
													</select>
												</div>
											</div>	
										</div>
										<div class="span2">
											<div class="control-group">
												<label class="control-label">Provincia</label>
												<div class="controls">
													<select name="pro" id="pro" class="span2" title="Provincia" onChange="changeCombo();" onClick="changeCombo();">
														<?php
														$cn = new PostgreSQL();
														$query = $cn->consulta("SELECT provinciaid,provnom FROM admin.provincia WHERE paisid LIKE '".$_POST['pais']."' AND departamentoid LIKE '".$_POST['dep']."'");
														if ($cn->num_rows($query) > 0) {
															while ($result = $cn->ExecuteNomQuery($query)) {
																if ($_POST['pro'] == $result['provinciaid']) {
																	echo "<option value='".$result['provinciaid']."' SELECTED>".$result['provnom']."</option>";
																}else{
																	echo "<option value='".$result['provinciaid']."'>".$result['provnom']."</option>";
																}
															}
														}
														$cn->close($query);
														?>
													</select>
												</div>
											</div>	
										</div>
										<div class="span2">
											<div class="control-group">
												<label class="control-label">Distrito</label>
												<div class="controls">
													<select name="dis" id="dis" class="span2" title="Distrito">
														<?php
														$cn = new PostgreSQL();
														$query = $cn->consulta("SELECT distritoid,distnom FROM admin.distrito WHERE paisid LIKE '".$_POST['pais']."' AND departamentoid LIKE '".$_POST['dep']."' AND provinciaid LIKE '".$_POST['pro']."'");
														if ($cn->num_rows($query) > 0) {
															while ($result = $cn->ExecuteNomQuery($query)) {
																if ($_POST['dis'] == $result['distritoid']) {
																	echo "<option value='".$result['distritoid']."' SELECTED>".$result['distnom']."</option>";
																}else{
																	echo "<option value='".$result['distritoid']."'>".$result['distnom']."</option>";
																}
															}
														}
														$cn->close($query);
														?>
													</select>
												</div>
											</div>	
										</div>
										<div class="span2">
											<div class="control-group">
												<label class="control-label">Telefono</label>
												<div class="controls">
													<input type="text" id="tel" name="tel" class="span2" value="<?php echo $_POST['tel']; ?>" maxlength="10" title="Telefono">
												</div>
											</div>	
										</div>
										<div class="span4">
											<siv class="control-group">
												<label class="control-label">Contacto</label>
												<div class="controls">
													<input type="text" id="con" name="con" value="<?php echo $_POST['con']; ?>" class="span4" title="Contacto de Cliente">
												</div>
											</siv>
										</div>
										<div class="span6">
											<div class="control-group">
												<div class="controls">
													<button type="Button" class="btn btn-success t-d" onClick="savedCustomers();"><i class="icon-ok"></i> Guardar Cambios</button>
													<button type="reset" class="btn btn-warning t-d"><i class="icon-trash"></i> Limpiar</button>
												</div>
											</div>
										</div>
									</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
	</section>
	<div id="space"></div>
	<footer></footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>