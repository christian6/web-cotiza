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
	<script type="text/javascript" src="../web-almacen/js/autocomplete.js"></script>
	<script src="js/medidastand.js"></script>
	<style>
		.ui-autocomplete {
			max-height: 13em;
			overflow-y: auto;
			/* prevent horizontal scrollbar */
			overflow-x: hidden;
		}
		caption{
			text-align: left;
		}
	</style>
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<header>
		<input type="hidden" id="txtpro" value="<?php echo $_GET['pro']; ?>" />
		<input type="hidden" id="txtsub" value="<?php echo $_GET['sub']; ?>" />
		<input type="hidden" id="txtsec" value="<?php echo $_GET['sec']; ?>" />
		<input type="hidden" id="adi" value="<?php echo $_GET['adi']; ?>" />
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
				<div class="span12">
					<div class="well c-blue-light t-info">
						<p>
							<i class="icon-chevron-right"></i>
							<strong>Debe de tener en cuenta para hacer un pedido.</strong> 
							<p style="text-indent: 40px;">
								<i class="icon-ok-sign"></i>
								El pedido puede ser atentido normalmente en no menos de 15 días
								y un poco mas de 20 días habiles, esto varia puede variar.
							</p>
							<p style="text-indent: 40px;">
								<i class="icon-ok-sign"></i>
								Los pedidos que contengan materiales para ser fabricados nececitan un promedio de 15 días para ser atendidos.
							</p>
							<p style="text-indent: 40px;">
								<i class="icon-ok-sign"></i>
								Si almacén no cuenta con <strong>Stock</strong> para los materiales que se estan solicitando el pedido puede ser atendido
								5 días a más.
							</p>
							<p style="text-indent: 40px;">
								<i class="icon-ok-sign"></i>
								Si almacén cuenta con <strong>Stock</strong> para los materiales que se estan solicitando el pedido puede ser atendido
								1 a 3 días.
							</p>
						</p>
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
											<div class="control-group pull-right">
												<label class="radio inline t-info">
													<input type="radio" name="rbchk" value="a" onChange="selectall();" /> Seleccionar Todo.
												</label>
												<label class="radio inline t-info">
													<input type="radio" name="rbchk" value="n" onChange="selectall();" /> No Seleccionar Ninguno.
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
												$sql = "SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant,d.flag
														FROM operaciones.metproyecto d INNER JOIN admin.materiales m
														ON d.materialesid LIKE m.materialesid
														INNER JOIN ventas.proyectos p
														ON d.proyectoid LIKE p.proyectoid ";
												if ($_GET['sub'] == "") {
													$sql .= "WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM(d.subproyectoid) LIKE '' AND TRIM(d.sector) LIKE TRIM('".$_GET['sec']."') GROUP BY d.materialesid,m.matnom,m.matmed,m.matund,d.flag";
												}elseif ($_GET['sub'] != "") {
													$sql .= "WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM(d.subproyectoid) LIKE TRIM('".$_GET['sub']."') AND TRIM(d.sector) LIKE TRIM('".$_GET['sec']."')  GROUP BY d.materialesid,m.matnom,m.matmed,m.matund,d.flag";
												}
												$query = $cn->consulta($sql);
												if ($cn->num_rows($query) > 0) {
													$i = 1;
													$tot = 0;
													$arrni = array();
													$j = 0;
													$count = 1;
													$flag = 1;
													while ($result = $cn->ExecuteNomQuery($query)) {
														if ($result['flag'] == 0) {
															echo "<tr class='c-green-light'>";
															echo "<td><input type='checkBox' DISABLED /></td>";
															
															if ( substr($result['materialesid'], 0,3) == '115') {
																$arrni[$j] = $result['materialesid'];
																$j++;
															}
															$flag++;
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
															$tot = ($tot + ($r[1] * $result['cant']));
														}else{
															echo "<td id='tc'>-</td>";
														}
														echo "</tr>";
														$count++;
													}
												}else{

												}
												$cn->close($query);
											?>
										</tbody>
										<tfoot>
											<tr>
												<input type="hidden" id="pto" value="<?php echo $tot; ?>">
												<input type="hidden" id="ptn" value="0">
											</tr>
										</tfoot>
									</table>
									
									<div class="well c-yellow-light">
										<a href="javascript:hideadicionales();" class="close">&times;</a>
										<h4 class='t-warning'>
											Modificación de Sector 	
											<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT MAX(status) FROM operaciones.modifysec WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' 
																		AND TRIM(sector) LIKE '".$_GET['sec']."' GROUP BY fec ORDER BY fec DESC LIMIT 1 OFFSET 0");
												if ($cn->num_rows($query) > 0) {
													$msec = $cn->ExecuteNomQuery($query);
												}
												$cn->close($query);
											?>
											<button id="btnadi" class="btn btn-warning btn-mini" onClick="showadicionales();" <?php if($count == $flag){echo "DISABLED";} if($msec[0] == '0' || $msec[0] == '1'){ echo "DISABLED"; } ?>><i class="icon-chevron-down"></i></button>
										</h4>
										<div id="adic" class='hide'>
											<table class='table table-condensed'>
												<caption>
													<div class='btn-group pull-left'>
														<button class='btn btn-warning t-d' onClick="showaddmat();"><i class='icon-list'></i> Agregar</button>
														<button class='btn btn-warning t-d' onClick="confirmok();"><i class='icon-check'></i> Listo</button>
													</div>
													<div id="addmat" class="row show-grid hide">
														<div class="span11">
															<a onClick="javsacript:hideaddmat();" class="close">&times;</a>
														<div class="span5">
															<div class="control-group info">
																<label for="controls" class="control-label">Descripción</label>
																<div class="controls">
																	<div class="ui-widget">
																		<select id="combobox" class="span5" onclick="showmed();" style="display: none;">
																		<?php
																			$cn = new PostgreSQL();
																			$query = $cn->consulta("SELECT DISTINCT m.matnom FROM admin.materiales m INNER JOIN almacen.inventario i ON m.materialesid=i.materialesid AND i.anio LIKE '".date("Y")."' ORDER BY matnom ASC");
																			if ($cn->num_rows($query)>0) {
																				while ($result = $cn->ExecuteNomQuery($query)) {
																					echo "<option value='".$result['matnom']."'>".$result['matnom']."</option>";
																				}
																			}
																			$cn->close($query);
																		?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
														<div class="span5">
															<div class="control-group info">
																<label for="controls" class="control-label">Medida</label>
																<div class="controls">
																	<select class="span5" name="cbomed" id="med" onClick="showdet();">
																	</select>
																</div>
															</div>
														</div>
														<div class="span5">
															<div class="well c-red t-white">
																<div id="data"></div>
															</div>
														</div>
														<div class="span5 well c-red-light">
																<div class="span2">
																	<div class="control-group info">
																		<label for="controls" class="control-label">Cantidad</label>
																		<div class="controls">
																			<input type="number" id="cant" min="0" max="9999" class="span2">
																		</div>
																	</div>
																</div>
																<div class="span1">
																	<div class="control-group">
																		<label for="controls" class="control-label">&nbsp;</label>
																		<div class="controls">
																			<button class="btn btn-warning" onClick="savemat();"><i class="icon-plus"></i></button>
																		</div>
																	</div>	
																</div>
														</div>
														</div>
													</div>
												</caption>
												<tbody id="dettbl">
												</tbody>
											</table>
										</div>
										<div class="row show-grid">
											<div class="span11">
												<div id="msgmo" class="alert alert-info alert-block pull-center <?php if($msec[0] == '2' || $msec[0] == '' || $msec[0] == '3'){ echo "hide"; }  ?>">
													<h4>Espere Aprobación</h4>
												</div>
											</div>
										</div>
									</div>

				 					<div class="row show-grid">
				 						<div class="span6">
				 							<div class="well c-yellow-light">
				 								<h4 class="t-warning">Niples  &nbsp;
				 									<!--<button onClick="niplesock();" class="btn btn-warning t-d pull-right"><i class="icon-ok-circle"></i> Listo</button>--></h4>
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
																		<button class="btn btn-mini btn-success" onClick='addniple(<?php echo str_replace('"', '', $result["matmed"]); ?>,"<?php echo $result['materialesid']; ?>");' <?php if($result['flag'] == '0'){echo "DISABLED";} ?>><i class="icon-plus"></i></button>
																		<button class="btn btn-mini btn-info" onClick='tmplist("<?php echo $result['materialesid']; ?>",<?php echo str_replace('"', '', $result["matmed"]); ?>);' <?php if($result['flag'] == '0'){echo "DISABLED";} ?>><i class="icon-refresh"></i></button>
																	</div>
																	<p class="pull-right t-warning help-inline"><label class="badge badge-info inline">Consumido <strong id="qd<?php echo str_replace('"', '', $result["matmed"]); ?>">0</strong> de <?php echo $result['cant']; ?></label>
																		<label class="help-inline badge badge-important"> Restante <strong id="tf<?php echo str_replace('"', '', $result["matmed"]); ?>"></strong></label></p>
																	<div class="" id="nip<?php echo str_replace('"', '', $result["matmed"]); ?>">
																		<?php
																			if (count($arrni) > 0) {
																				//$nc = 0;
																				for ($i=0; $i < count($arrni); $i++) { 
																					if ($result['materialesid'] == $arrni[$i]) {
																						$c = new PostgreSQL();
																						$q = $c->consulta("SELECT nropedido,materialesid,metrado,tipo FROM operaciones.niples 
																							WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE TRIM('".$_GET['sub']."') 
																							AND TRIM(sector) LIKE TRIM('".$_GET['sec']."') AND materialesid LIKE '".$arrni[$i]."'");
																						if ($c->num_rows($q) > 0) {
																							echo "<table class='table table-hover table-condensed'>";
																							while ($res = $c->ExecuteNomQuery($q)) {
																								echo "<tr>";
																								echo "<td>".$res['nropedido']."</td>";
																								echo "<td>".$res['materialesid']."</td>";
																								echo "<td>".$res['metrado']."</td>";
																								echo "<td>".$res['tipo']."</td>";
																								echo "</tr>";
																							}
																							echo "</table>";
																						}
																						$c->close($q);
																					}
																				}
																			}
																		?>
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
				 						<div class="span5 well">
				 							<h5 class="t-info">Escribe alguna observacion para el sector <?php echo $_GET['sec']; ?></h5>
											<div class="control-group">
												<div class="controls">
													<textarea name="obsec" id="obsec" rows="1" maxlenght="320" onFocus="onobs();" onBlur="obsblur();" style="width: 97%;"></textarea>
												</div>
											</div>
											<div class="controls">
												<button class="btn btn-success t-d" OnClick="savemsgsec();"><i class="icon-comment"></i> Publicar</button>
												<small class="t-info" onClick="savemsgsec();">Solo se admiten 320 caracteres.</small>
											</div>
											<hr>
											<div style='width: 97%;'>
												<?php
													$cn = new PostgreSQL();
													$query = $cn->consulta("SELECT id,to_char(fecha, 'HH24:MI DD/MM/YYYY') as fec,msg,tm FROM ventas.alertasec WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' 
																			AND TRIM(sector) LIKE '".$_GET['sec']."' ORDER BY fecha DESC");
													if ($cn->num_rows($query) >= 1) {
														while ($result = $cn->ExecuteNomQuery($query)) {
															if ($result['tm'] == 'v') {
																echo "<div class='alert alert-success pull-right'>";
																//echo "<a class='close'>&times;</a>";
																echo "<strong>Ventas <span class='pull-right'>".$result['fec']."</span> </strong>";
																echo "<p>".$result['msg']."</p>";
																echo "</div>";
															}else if($result['tm'] == 'o'){
																echo "<div class='alert alert-waring pull-left'>";
																echo "<strong>Operaciones <span class='pull-right'>".$result['fec']."</span> </strong>";
																echo "<p>".$result['msg']."</p>";
																echo "</div>";
															}
														}
													}
													$cn->close($query);
												?>
											</div>
				 						</div>
				 						<!--<div class="span5">
				 							<div class="well c-blue-light t-info">
				 								<h4>Información Adicional</h4>
				 								<p>
				 									En esta sección puedes subir información referente a tu pedido.
				 								</p>
				 								<input type="file">
				 							</div>
				 						</div>-->
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
							<input type="text" class="span1" id="txtdni" name="txtdni" value="<?php echo $_SESSION['dni-icr']; ?>" DISABLED />
							<input type="text" class="span4" id="txtnom" name="txtnom" value="<?php echo $_SESSION['nom-icr']; ?>" DISABLED />
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
							<textarea name="txtobser" id="txtobser" class="span4" rows="3"></textarea>
						</div>
					</div>
					<div class="control-group">
						<div id="cad" class="well pull-center c-gd">
							<a href="javascript:openadj();" style="color: #FFBF00;">Archivo Adicional</a>
						</div>
						<input type="file" id="fileadj" onChange="fchan();" class="hide" accept="application/pdf"/>
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
				<a class="close" data-dismiss="modal">&times;</a>
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
										WHERE TRIM(p.proyectoid) LIKE '".$_GET['pro']."' AND TRIM(p.subproyectoid) LIKE '".$_GET['sub']."' AND 
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
		<div id="mconfirm" class="modal fade in c-yellow-light t-warning hide">
			<div class="modal-header">
				<a href="#" data-dismiss="modal" class="close">&times;</a>
				<h4>Movito de Modificación</h4>
			</div>
			<div class="modal-body">
				<div class="control-group info">
					<label for="controls" class="control-label">Especificar motivo de la modificación</label>
					<div class="controls">
						<textarea name="txtmot" id="txtmot" class="span5" rows="5"></textarea>
					</div>
				</div>
				<div class="controls">
					<button class="btn btn-warning t-d" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
					<button class="btn btn-info t-d pull-right" onClick="saveconfirm();"><i class="icon-ok"></i> Guardar</button>
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