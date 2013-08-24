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
	<title>Sectores y Subproyectos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/pedido.js"></script>
	<script src="../modules/msgBox.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<header>
		<input type="hidden" id="txtpro" value="<?php echo $_GET['pro']; ?>" />
		<input type="hidden" id="txtsub" value="<?php echo $_GET['sub']; ?>" />
		<input type="hidden" id="txtsec" value="<?php echo $_GET['sec']; ?>" />
	</header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Home</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="aprobados.php">Proyecto</a>
				<span class="divider">/</span>
			</li>
			<li> 
				<a href="sectorsub.php?pro=<?php echo $_GET['pro']; ?>">Admin Proyecto</a>
				<span class="divider">/</span>
			</li>
			<li class="active"><?php echo $_GET['sec']; ?></li>
		</ul>
	</div>
	<section>
		<div class="container well">
			<div class="row show-grid">
				<div class="span12">
					<h3>Pedido de Materiales de <?php echo $_GET['sec']; ?></h3>
					<div class="row">
						<div class="row">
							
						
					<?php
					$cn = new PostgreSQL();
					$sql = "SELECT p.descripcion,b.subproyecto,s.sector FROM ventas.proyectos p
							INNER JOIN ventas.subproyectos b
							ON p.proyectoid LIKE b.proyectoid
							INNER JOIN ventas.sectores s
							ON p.proyectoid LIKE s.proyectoid WHERE ";
					if ($_GET['sub'] != "") {
						$sql .= " s.proyectoid LIKE '".$_GET['pro']."' AND TRIM(s.subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(s.nroplano) LIKE TRIM('".$_GET['sec']."')";
					}else{
						$sql .= " s.proyectoid LIKE '".$_GET['pro']."' AND TRIM(s.subproyectoid) LIKE '' AND TRIM(s.nroplano) LIKE TRIM('".$_GET['sec']."')";
					}
					//echo $sql;
					$query = $cn->consulta($sql);
					if ($cn->num_rows($query) > 0) {
						$result = $cn->ExecuteNomQuery($query);
						echo "<dl class='dl-horizontal'>";
						echo "<dt> Proyecto</dt>";
						echo "<dd>".$result['descripcion']."</dd>";
						if ($_GET['sub'] != "") {
							echo "<dt> subproyecto</dt>";
							echo "<dd>".$result['subproyecto']."</dd>";
						}
						echo "<dt> Sector</dt>";
						echo "<dd>".$result['sector']."</dd>";
					}else{

					}
					$cn->close($query);
					?>
					</div>
					</div>
				</div>
				<div class="span4">
					<div class="btn-group">
						<button class="btn btn-warning t-d" onClick="showpedido();"><i class="icon-th-large"></i> Pedido</button>
						<button class="btn btn-warning t-d" onClick="showlist();"><i class="icon-eye-open"></i> Ver</button>
						<a href="sectorsub.php?pro=<?php echo $_GET['pro']; ?>&sub=<?php echo $_GET['sub']; ?>" class="btn btn-success"><i class="icon-arrow-left"></i> Volver</a>
					</div>
				</div>
			</div>
			
					<ul id="tab" class="nav nav-tabs">
						<li class="active"><a href="#mat" data-toggle="tab">Materiales</a></li>
						<li><a href="#mo" data-toggle="tab">Mano de Obra</a></li>
						<li><a href="#eh" data-toggle="tab">Equipos y Herramientas</a></li>
					</ul>
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane fade in active" id="mat">

								<div class="well">
									<table class="table table-bordered table-condensed table-hover">
										<caption>
											<div class="control-group">
												<label for="label" class="inline pull-left">
													<i class="c-green-light">&nbsp;&nbsp;&nbsp;&nbsp;</i>
													Material ya pedidos.
												</label>
												<label for="label" class="inline pull-left">
													<i class="c-yellow-light">&nbsp;&nbsp;&nbsp;&nbsp;</i>
													Materiales pendientes.
												</label>
											</div>

										</caption>
										<thead>
											<th></th>
											<th>Item</th>
											<th>Codigo</th>
											<th>Descripción</th>
											<th>Medida</th>
											<th>Unidad</th>
											<th>Cantidad</th>
											<th>Stock</th>
										</thead>
										<tbody>
											<?php
												$cn = new PostgreSQL();
												$sql = "SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant,flag
														FROM operaciones.metproyecto d INNER JOIN admin.materiales m
														ON d.materialesid LIKE m.materialesid
														INNER JOIN ventas.proyectos p
														ON d.proyectoid LIKE p.proyectoid ";
												if ($_GET['sub'] == "") {
													$sql .= "WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM(d.subproyectoid) LIKE '' AND TRIM(d.sector) LIKE TRIM('".$_GET['sec']."') GROUP BY d.materialesid,m.matnom,m.matmed,m.matund,flag";
												}elseif ($_GET['sub'] != "") {
													$sql .= "WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM(d.subproyectoid) LIKE TRIM('".$_GET['sub']."') AND TRIM(d.sector) LIKE TRIM('".$_GET['sec']."')  GROUP BY d.materialesid,m.matnom,m.matmed,m.matund,flag";
												}
												$query = $cn->consulta($sql);
												if ($cn->num_rows($query) > 0) {
													$i = 1;
													while ($result = $cn->ExecuteNomQuery($query)) {
														
														if ($result['flag'] == 0) {
															echo "<tr class='c-green-light'>";
															echo "<td><input type='checkBox' DISABLED /></td>";
														}else{
															echo "<tr class='c-yellow-light'>";
															echo "<td><input type='checkBox' name='mats' id='".$result['materialesid']."'></td>";	
														}
														$c = new PostgreSQL();
														$q = $c->consulta("SELECT * FROM operaciones.sp_search_stock_mat('".$result['materialesid']."');");
														if ($c->num_rows($q) > 0) {
															$r = $c->ExecuteNomQuery($q);
														}
														$c->close($q);
														echo "<td id='tc'>".$i++."</td>";
														echo "<td>".$result['materialesid']."</td>";
														echo "<td>".$result['matnom']."</td>";
														echo "<td>".$result['matmed']."</td>";
														echo "<td id='tc'>".$result['matund']."</td>";
														echo "<td id='tc'>".$result['cant']."</td>";
														if ($r[0] >= 0) {
															echo "<td id='tc'>".$r[0]."</td>";
														}else{
															echo "<td id='tc'>-</td>";
														}
														echo "</tr>";
													}
												}else{

												}
												$cn->close($query);
											?>
										</tbody>
									</table>
									
				 					<div class="row show-grid">
				 						<div class="span6">
				 							<div class="well c-yellow-light">
				 								<h4 class="t-warning">Niples  &nbsp;<button onClick="niplesok();" class="btn btn-warning t-d pull-right"><i class="icon-ok-circle"></i> Listo</button></h4>
				 								<div class="accordion" id="niples">
				 									<?php
				 									$cn = new PostgreSQL();
				 									$query = $cn->consulta("SELECT DISTINCT d.materialesid,m.matnom,TRIM(m.matmed) as matmed,m.matund,SUM(d.cant) as cant,flag
															FROM operaciones.metproyecto d INNER JOIN admin.materiales m
															ON d.materialesid LIKE m.materialesid
															INNER JOIN ventas.proyectos p
															ON d.proyectoid LIKE p.proyectoid 
															WHERE d.materialesid LIKE '115%' AND d.proyectoid LIKE '".$_GET['pro']."' AND 
															TRIM(d.subproyectoid) LIKE TRIM('".$_GET['sub']."') AND TRIM(d.sector) LIKE TRIM('".$_GET['sec']."')
															GROUP BY d.materialesid,m.matnom,m.matmed,m.matund,flag
															");
				 									if ($cn->num_rows($query) > 0) {
				 										while ($result = $cn->ExecuteNomQuery($query)) {
				 									?>
													<div class="accordion-group">
														<div class="accordion-heading c-blue-light">
															<a class="accordion-toggle" data-toggle="collapse" data-parent="#niples" href="#coll<?php echo $result['matmed']; ?>">
																<div class="control-group">
																	<span class="inline">
																		<?php echo $result['matnom']." - ".$result['matmed']; ?>
																	</span>
																	<span class="badge badge-info pull-right"><strong id="ct<?php echo str_replace('"', '', $result["matmed"]); ?>"><?php echo $result['cant']."</strong> ".$result['matund']; ?></span>
																</div>
															</a>
														</div>
														<div id="coll<?php echo $result['matmed']; ?>" class="accordion-body collapse">
															<div class="accordion-inner c-blue-light">
																<div class="alert-block">
																	<div class="btn-group inline">
																		<button class="btn btn-mini btn-success" onClick='addniple(<?php echo str_replace('"', '', $result["matmed"]); ?>,"<?php echo $result['materialesid']; ?>");'><i class="icon-plus"></i></button>
																		<button class="btn btn-mini btn-danger"><i class="icon-trash"></i></button>
																		<button class="btn btn-mini btn-info" onClick='tmplist("<?php echo $result['materialesid']; ?>",<?php echo str_replace('"', '', $result["matmed"]); ?>);'><i class="icon-refresh"></i></button>
																	</div>
																	<p class="pull-right t-warning help-inline"><label class="badge badge-info inline">Consumido <strong id="qd<?php echo str_replace('"', '', $result["matmed"]); ?>">0</strong> de <?php echo $result['cant']; ?></label>
																		<label class="help-inline badge badge-important"> Restante <strong id="tf<?php echo str_replace('"', '', $result["matmed"]); ?>"></strong></label></p>
																	<div class="" id="nip<?php echo str_replace('"', '', $result["matmed"]); ?>">
																		
																	</div>
																</div>
															</div>
														</div>
													</div>
													<?php
														}
													}
													$cn->close($query);
													?>
												</div>

				 							</div>
				 						</div>
				 						<div class="span5">
				 							<div class="well c-blue-light t-info">
				 								<h4>Información Adicional</h4>
				 								<p>
				 									En esta sección puedes subir información referente a tu pedido.
				 								</p>
				 								<input type="file">
				 							</div>
				 						</div>
				 					</div>
								</div>
			 				
						</div>
						<div class="tab-pane fade in" id="mo">
							<div class="row">
								<div class="span11" style="background-color: rgba(0,0,0,.5); border-radius: .5em;">
									<span>
										<h4 style="text-align: center;">No Disponible</h4>
									</span>
								</div>
			 				</div>
						</div>
						<div class="tab-pane fade in" id="eh">
							<div class="row">
								<div class="span11" style="background-color: rgba(0,0,0,.5); border-radius: .5em;">
									<span>
										<h4 style="text-align: center;">No Disponible</h4>
									</span>
								</div>
			 				</div>
						</div>
					</div>
				</div>
			
		<div id="mpe" class="modal fade in hide">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<h4>Pedido a Almacén</h4>
			</div>
			<div class="modal-body">
				<div class="group-control">
					<label for="cboal" class="control-label t-info">Almacén</label>
					<div class="controls">
						<select name="cboal" id="cboal">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT almacenid, descri FROM admin.almacenes WHERE esid LIKE '21'");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)){
									echo "<option value='".$result['almacenid']."'>".$result['descri']."</option>";
								}
							}
							$cn->close($query);
						?>
						</select>
					</div>
					<div class="control-group">
						<label for="label" class="control-label t-info">Empleado</label>
						<div class="controls">
							<input type="text" class="input-small" id="txtdni" name="txtdni" value="<?php echo $_SESSION['dni-icr']; ?>" DISABLED />
							<input type="text" class="input-xlarge" id="txtnom" name="txtnom" value="<?php echo $_SESSION['nom-icr']; ?>" DISABLED />
						</div>
					</div>
					<div class="control-group">
						<label for="label" class="control-label t-info">Fecha Requerida</label>
						<div class="controls">
							<input type="text" class="input-small" id="txtfec" placeholder="aaaa-mm-aa" title="Ingrese Fecha"/>
						</div>
					</div>
					<div class="control-group">
						<label for="label" class="control-label t-info">Observación</label>
						<div class="controls">
							<textarea name="txtobser" id="txtobser" class="input-xlarge" rows="3"></textarea>
						</div>
					</div>
				</div>
				<div class="progress progress-warning progress-striped active hide">
				  <div class="bar" style="width: 100%;"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn  pull-left" data-dismiss="modal"><i class="icon-resize-small"></i> Cancelar</button>
				<button class="btn btn-primary pull-right" onClick="savepedido();"><i class="icon-ok icon-white"></i> Guardar Cambios</button>
			</div>
		</div>
		<div id="mlist" class="modal fade in hide">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<h5>Lista de Pedidos</h5>
			</div>
			<div class="modal-bady">
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT p.nropedido,p.fecha::date,p.fecent,a.descri,s.esnom FROM almacen.pedido p 
										INNER JOIN admin.almacenes a
										ON p.almacenid = a.almacenid
										INNER JOIN admin.estadoes s
										ON p.esid LIKE s.esid
										WHERE p.proyectoid LIKE '".$_GET['pro']."' AND TRIM(p.subproyectoid) LIKE '".$_GET['sub']."' AND 
										TRIM(sector) LIKE '".$_GET['sec']."'");
				if ($cn->num_rows($query) > 0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<div class='c-warning p10'>";
						//190.41.246.91/web/reports/almacen/pdf/rptpedidomat.php?nro=000000000000012
						//echo "<p>";
						echo "<a class='t-d' href='../reports/almacen/pdf/rptpedidomat.php?nro=".$result['nropedido']."' target='_blank'>";
						echo "<label class='label label-info'>Nro Pedido: </label>
						".$result['nropedido']." <i class='icon-eye-open'></i>";
						echo "<p>";
						//echo "<label class='label label-info'>Fecha: </label>
						//".$result['fecha']." ";
						echo "<label class='label label-info'>Entrega: </label>
						".$result['fecent']." ";
						echo "<label class='label label-info'>Almacén: </label>
						".$result['descri']." ";
						echo "<label class='label label-info'>Estado: </label>
						".$result['esnom']." ";
						echo "</p>";
						echo "</a>";
						//echo "</p>";
						echo "</div>";
					}
				}
				$cn->close($query);
				?>
			</div>
			<div class="modal-footer">
				<button class="btn pull-left" data-dismiss="modal"><i class="icon-resize-small"></i> Cancelar</button>

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