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
	<title>Administrar Detalle de Sector</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<script src="../modules/msgBox.js"></script>
	<script src="js/manager-detsec.js"></script>
	<style>
		#fullpdf{
			display: none;
			margin-top: 5em;
			position: absolute;
			/*top: 1em;*/
		}
		#fullscreen-icr button{
			position: absolute;
			top: 3em;
		}
		#plano{
			background-color: #2E3134;
			border: .3em dashed gray;
			border-radius: .3em;
			color: #7f858a;
			font-size: 1em;
			font-weight: bold;
			padding: .5em;
			text-align: center;
			text-transform: uppercase;
		}
		#tblm thead tr th, #tblm tfoot tr th{
			background-color:#8B0000;
		}
		#cpre{
			text-align: center;
		}
	</style>
	<script>
		$(function () {
			resizesmall();
		});
		function resizesmall () {
			$( "#plano" ).animate({
				height: "2em"
			},1000);
			$("#vpdf").css('display','none');
		}
		function resizefull () {
			$( "#plano" ).animate({
				height: "31em"
			},1000);
			$( "#vpdf").css('display','block');
		}
		function openfull () {
			$( "#fullscreen-icr" ).show("clip",{},1600);
			$("#fullpdf").css('display','block');
		}
		function closefull () {
			$( "#fullscreen-icr" ).hide("clip",{},2000);
		}
	</script>
</head>
<body>
	<?php include ("includes/menu-manager.inc"); ?>
	<header>
		<input type="hidden" id="pro" value="<?php echo $_GET['pro']; ?>">
		<input type="hidden" id="sub" value="<?php echo $_GET['sub']; ?>">
		<input type="hidden" id="sec" value="<?php echo $_GET['sec']; ?>">
	</header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Inicio</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="manager-pro.php">Admin. Proyecto</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="manager-sec.php?pro=<?php echo $_GET['pro']; ?>">Admin. Sectores</a>
				<span class="divider">/</span>
			</li>
			<li class="active"><?php echo $_GET['sec']; ?></li>
		</ul>
	</div>
	<section>
		<div class="container well">
			<h3 class="t-warning">Sector <?php echo $_GET['sec']; ?></h3>
			<?php
				$dir = "";
				$file = -1;
				if ($_GET['sub'] != '') {
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['pro']."/".$_GET['sub']."/".$_GET['sec'].".pdf")) {
						$dir = "/web/project/".$_GET['pro']."/".$_GET['sub']."/".$_GET['sec'].".pdf";	
						$file = 1;
					}
				}else{
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['pro']."/".$_GET['sec'].".pdf")) {
						$dir = "/web/project/".$_GET['pro']."/".$_GET['sec'].".pdf";
						$file = 1;
					}
				}
			?>
			<?php if ($file == 1){ ?>
			<div class="row show-grid">
				<div class="span12">
					<div id="plano">
						<div class="btn-group pull-left">
							<button class="btn" onClick="openfull();"><i class="icon-eye-open"></i></button>
							<button class="btn" onClick="resizesmall();"><i class="icon-resize-small"></i></button>
							<button class="btn" onClick="resizefull();"><i class="icon-resize-full"></i></button>
						</div>
						<iframe id="vpdf" src="<?php echo $dir; ?>" width="100%" height="400" frameborder="0"></iframe>
					</div>
				</div>
			</div>
			<?php } ?>
			<br>
			<table class="table table-condensed table-hover table-bordered">
				<thead>
					<th></th>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Cantidad</th>
					<th>Stock</th>
					<th>Precio</th>
					<th>Importe</th>
				</thead>
				<tbody>
				<?php
					$cn = new PostgreSQL();
					$qsql = "";
					$import = 0;
					$total = 0;
					$qsql = "SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant
							FROM operaciones.metproyecto d INNER JOIN admin.materiales m
							ON d.materialesid LIKE m.materialesid
							INNER JOIN ventas.proyectos p
							ON d.proyectoid LIKE p.proyectoid ";
					$qsql .= "WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' 
							AND TRIM(d.sector) LIKE TRIM('".$_GET['sec']."') GROUP BY d.materialesid,m.matnom,m.matmed,m.matund";
					$query = $cn->consulta($qsql);
					if ($cn->num_rows($query) > 0) {
						$i = 1;
						$arrni = array();
						$j = 0;
						while ($result = $cn->ExecuteNomQuery($query)) {
							if ( substr($result['materialesid'], 0,3) == '115') {
								$arrni[$j] = $result['materialesid'];
								$j++;
							}
							echo "<tr>";
							echo "<td id='tc'>".$i++."</td>";
							echo "<td>".$result['materialesid']."</td>";
							echo "<td>".$result['matnom']."</td>";
							echo "<td>".$result['matmed']."</td>";
							echo "<td id='tc'>".$result['matund']."</td>";
							echo "<td id='tc'>".$result['cant']."</td>";
							$c = new PostgreSQL();
							$q = $c->consulta("SELECT * FROM operaciones.sp_search_stock_mat('".$result['materialesid']."');");
							if ($c->num_rows($q) > 0) {
								$r = $c->ExecuteNomQuery($q);
								echo "<td id='tc'>".$r[0]."</td>";
								echo "<td id='tc'>".number_format($r[1],2)."</td>";
								echo "<td style='text-align: right;'>".number_format($result['cant'] * $r[1],2)."</td>";
							}else{
								echo "<td id='tc'>-</td>";
								echo "<td id='tc'>-</td>";
								echo "<td id='tc'>-</td>";
							}
							$c->close($q);
							$total += ($result['cant'] * $r[1]);

							//echo "<td id='tc'><a href='javascript:conedit(".$result['materialesid'].");'><i class='icon-pencil'></i></a></td>";
							//echo "<td id='tc'><a href='javascript:delmat(".$result['materialesid'].");'><i class='icon-remove'></i></a></td>";
							echo "</tr>";
						}
					}
			              					
					$cn->close();
				?>
				</tbody>
				<tfoot>
					<td colspan="8" style="text-align: right; background-color: rgba(8,106,135,1); color: #FFF;"><strong>Total</strong></td>
					<th class="c-blue-light" style="text-align:right;"><?php echo number_format($total,2); ?></th>
				</tfoot>
			</table>
			<div class="row show-grid">
				<div class="span12">
					<div class="well c-yellow-light t-warning">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT MAX(status),obs FROM operaciones.modifysec WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$_GET['sec']."' 
													GROUP BY fec,obs ORDER BY fec DESC LIMIT 1 OFFSET 0");
							if ($cn->num_rows($query) > 0) {
								$result = $cn->ExecuteNomQuery($query);
							}
							$cn->close($query);
						?>
						<a href="javascript:hidemsec();" class="close">&times;</a>
						<h4>Modificaciones del Sector <?php echo $_GET['sec']; ?> <button id="btnmsec" OnClick="showmsec();" class="btn btn-mini btn-warning" value="<?php echo $result[0]; ?>" <?php if($result[0] != '0'){ echo "DISABLED"; } ?>><i class="icon-chevron-down"></i></button></h4>
						<div id="msec">
							<?php //if($result['obs'] != ""){ ?>
							<div class="alert alert-info" style="width: 95%;">
									<a href="#" data-dismiss="alert" class="close">&times;</a>
									<strong>Motivo de la modificación del sector <?php echo $_GET['sec']; ?></strong>
									<p>
										<?php echo $result['obs']; ?>
									</p>
							</div>
							<?php
							//} 
							if ($result[0] == '0'){ ?>
								<table id="tblm" class="table table-condensed t-d">
									<tbody>
									<?php
										$cn = new PostgreSQL();
										$query = $cn->consulta("SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant,d.flag
													FROM operaciones.tmpmodificaciones d INNER JOIN admin.materiales m
													ON d.materialesid LIKE m.materialesid
													INNER JOIN ventas.proyectos p
													ON d.proyectoid LIKE p.proyectoid 
													WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM(d.subproyectoid) LIKE TRIM('".$_GET['sub']."') AND TRIM(d.sector) LIKE '".$_GET['sec']."'
													GROUP BY d.materialesid,m.matnom,m.matmed,m.matund,d.flag");
										if ($cn->num_rows($query) > 0) {
											echo "<thead>";
											echo "<tr>";
												echo "<th></th>";
												echo "<th>Codigo</th>";
												echo "<th>Nombre</th>";
												echo "<th>Medida</th>";
												echo "<th>Unidad</th>";
												echo "<th>Cantidad</th>";
												echo "<th>Stock</th>";
												echo "<th>Precio</th>";
												echo "<th>Importe</th>";
												echo "</tr>";
											echo "</thead>";
											$i=1;
											$tot = 0;
											while ($result = $cn->ExecuteNomQuery($query)) {
												if($result['flag'] == '0'){ echo "<tr class='c-red-light'>";}else{ echo "<tr>"; }
													echo "<td id='tc'>".$i++."</td>";
													echo "<td>".$result['materialesid']."</td>";
													echo "<td>".$result['matnom']."</td>";
													echo "<td>".$result['matmed']."</td>";
													echo "<td id='tc'>".$result['matund']."</td>";
													echo "<td id='tc'>".$result['cant']."</td>";
													$c = new PostgreSQL();
													$q = $c->consulta("SELECT * FROM operaciones.sp_search_stock_mat('".$result['materialesid']."');");
													if ($c->num_rows($q) > 0) {
														$r = $c->ExecuteNomQuery($q);
														echo "<td id='tc'>".$r[0]."</td>";
														echo "<td id='tc'>".number_format($r[1],2)."</td>";
														echo "<td style='text-align: right;'>".number_format($result['cant'] * $r[1],2)."</td>";
														$tot += ($result['cant'] * $r[1]);
													}else{
														echo "<td id='tc'>-</td>";
														echo "<td id='tc'>-</td>";
														echo "<td id='tc'>-</td>";
													}
													$c->close($q);
													//echo "<td><button class='btn btn-mini btn-danger' OnClick=delmodifymat('".$result['materialesid']."');><i class='icon-remove'></i></td>";
													echo "</tr>";
													
											}
										}
										$cn->close($query);
									?>
									</tbody>
									<tfoot>
										<tr>
											<th colspan="8" style="text-align: right; color: #FFF;">Total</th>
											<td style="text-align: right;" class='c-red-light'><?php echo number_format($tot,2); ?></td>
										</tr>
									</tfoot>
								</table>
								<div class="well">
									<div id="cpre">
										<div class="row show-grid">
											<div class="span3">
												<div class="alert alert-block alert-success">
													<strong>Precio de Original del Sector</strong><br>
													<h5 class=" alert t-white label-success">
														<?php echo number_format($total,2); ?>
													</h5>
												</div>
											</div>
											<div class="span3">
												<div class="alert alert-block alert-error">
													<strong>Nuevo Precio del Sector</strong><br>
													<h5 class="alert t-white label-important">
														<?php echo number_format($tot,2); ?>
													</h5>
												</div>
											</div>
											<div class="span3">
												<div class="alert alert-block alert-info">
													<strong>Diferencia de Precios</strong><br>
													<h5 class="alert t-white label-info">
														<?php if($tot > $total){ echo number_format(($tot - $total),2); }else{ echo number_format(($total - $tot),2);}?>
													</h5>
												</div>
											</div>
											<div class="span2">
												<div class="alert alert-waring alert-block">
													<button class="btn btn-success t-d btn-small input-small" onClick="aprobar();"><i class="icon-check"></i> Aprobar</button>
													<button class="btn btn-danger t-d btn-small input-small" onClick="anular();"><i class="icon-remove-sign"></i> Anular</button>
													<button class="btn btn-warning t-d btn-small input-small" onClick="shownewadi();"><i class="icon-plus-sign"></i> Adicional</button>	
												</div>
											</div>
										</div>
									</div>
								</div>
								
								
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="span6">
				 	<div class="well c-yellow-light">
						<h4 class="t-warning">Niples  &nbsp;</h4>
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
									$nmed = str_replace('"', '', $result["matmed"]);
									$nmed = str_replace('/', 'l', $nmed);
							?>
							<div class="accordion-group">
								<div class="accordion-heading c-blue-light">
									<a class="accordion-toggle" data-toggle="collapse" data-parent="#niples" href="#coll<?php echo $nmed; ?>">
										<div class="control-group">
											<span class="inline">
												<?php echo $result['matnom']." - ".$result['matmed']; ?>
											</span>
											<span class="badge badge-info pull-right"><strong id="ct<?php echo str_replace('"', '', $result["matmed"]); ?>"><?php echo $result['cant']."</strong> ".$result['matund']; ?></span>
										</div>
									</a>
								</div>
								<div id="coll<?php echo $nmed; ?>" class="accordion-body collapse">
									<div class="accordion-inner c-blue-light">
										<div class="alert-block">
											<div class="" id="nip<?php echo str_replace('"', '', $result["matmed"]); ?>">
												<?php
													if (count($arrni) > 0) {
														//$nc = 0;
														for ($i=0; $i < count($arrni); $i++) { 
															if ($result['materialesid'] == $arrni[$i]) {
																$c = new PostgreSQL();
																$q = $c->consulta("SELECT nropedido,materialesid,metrado,tipo FROM operaciones.niples 
																	WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE TRIM('".$_GET['sub']."') 
																	AND TRIM(sector) LIKE TRIM('".$_GET['sec']."') AND materialesid LIKE '".$arrni[$i]."' and flag like '1'");
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
					<div class="">
						<h5 class="t-info">Escribe alguna observacion para el sector <?php echo $_GET['sec']; ?></h5>
						<div class="control-group">
							<div class="controls">
								<textarea name="obsec" id="obsec" rows="1" maxlenght="320" onFocus="onobs();" onBlur="obsblur();" style="width: 97%;"></textarea>
							</div>
						</div>
						<div class="controls">
							<button class="btn btn-success t-d" OnClick="savemsgsec();"><i class="icon-comment"></i> Publicar</button>
							<small class="t-info">Solo se admiten 320 caracteres.</small>
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
											echo "<div class='alert alert-success pull-left' style='width: 26em;'>";
											//echo "<a class='close'>&times;</a>";
											echo "<strong>Ventas <span class='pull-right'>".$result['fec']."</span> </strong>";
											echo "<p>".$result['msg']."</p>";
											echo "</div>";
										}else if($result['tm'] == 'o'){
											echo "<div class='alert alert-waring pull-right' style='width: 26em;'>";
											echo "<strong>Operaciones <span class='pull-right'>".$result['fec']."</span> </strong>";
											echo "<p>".$result['msg']."</p>";
											echo "</div>";
										}else if($result['tm'] == 'a'){
											echo "<div class='alert alert-info' style='width: 26em;'>";
											echo "<strong>Gerencia <span class='pull-right'>".$result['fec']."</span> </strong>";
											echo "<p>".$result['msg']."</p>";
											echo "</div>";
										}
									}
								}
								$cn->close($query);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="mnewsec" class="modal fade in hide c-yellow-light t-warning">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3>Generar Adicional</h3>
			</div>
			<div class="modal-body">
				<div class="row show-grid">
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="control-label">Codigo Sector</label>
							<div class="controls">
								<input type="text" class="span2" maxlenght="10" value="<?php echo $_GET['sec']; ?>" DISABLED />
							</div>
						</div>		
					</div>
					<div class="span3">
						<div class="control-group info">
							<label for="controls" class="control-label">Numero de Orden de Compra</label>
							<div class="controls">
								<input type="text" class="span2" maxlenght="10" id="noc">
							</div>
						</div>
					</div>
					<div class="span5">
						<div class="control-group info">
							<label for="controls" class="control-label">Descripción de Adicional</label>
							<div class="controls">
								<input type="text" id="adides" class="span5">
							</div>
						</div>
					</div>
					<div class="span5">
						<div class="control-group info">
							<label for="controls" class="control-label">Observacion de Adicional</label>
							<div class="controls">
								<textarea name="adiobs" id="adiobs" class="span5" rows="5"></textarea>
							</div>
						</div>
					</div>
					<div class="span5">
						<div class="controls">
							<button class="btn" data-dismiss='modal'><i class="icon-remove"></i> Cancelar</button>
							<button class="btn pull-right btn-warning t-d" OnClick="nextadicional();"><i class="icon-chevron-right"></i> Continuar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="mlistadi" class="modal fade in hide container" style="margin-left: -43%;">
			<div class="modal-header">
				<a data-dismiss="modal" class="close"></a>
				<h4>Lista de Adicional</h4>
			</div>
			<div class="modal-body">
				<table id="tblm" class="table table-bordered table-condensed t-d">
					<tbody>
					<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant,d.flag
									FROM operaciones.tmpmodificaciones d INNER JOIN admin.materiales m
									ON d.materialesid LIKE m.materialesid
									INNER JOIN ventas.proyectos p
									ON d.proyectoid LIKE p.proyectoid 
									WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM(d.subproyectoid) LIKE TRIM('".$_GET['sub']."') AND TRIM(d.sector) LIKE '".$_GET['sec']."'
									GROUP BY d.materialesid,m.matnom,m.matmed,m.matund,d.flag");
						if ($cn->num_rows($query) > 0) {
							echo "<thead>";
							echo "<tr>";
								echo "<th></th>";
								echo "<th></th>";
								echo "<th>Codigo</th>";
								echo "<th>Nombre</th>";
								echo "<th>Medida</th>";
								echo "<th>Unidad</th>";
								echo "<th>Cantidad</th>";
								echo "</tr>";
							echo "</thead>";
							$i=1;
							while ($result = $cn->ExecuteNomQuery($query)) {
								if($result['flag'] == '0'){ 
									echo "<tr class='c-red-light'>";
									echo "<td id='tc'>".$i++."</td>";
									echo "<td><input type='radio' DISABLED><td>";
								}else{ 
									echo "<tr>";
									echo "<td id='tc'>".$i++."</td>";
									echo "<td><input type='checkbox' name='maid' value='".$result['materialesid']."'></td>";
								}
									echo "<td>".$result['materialesid']."</td>";
									echo "<td>".$result['matnom']."</td>";
									echo "<td>".$result['matmed']."</td>";
									echo "<td id='tc'>".$result['matund']."</td>";
									echo "<td id='tc'>".$result['cant']."</td>";
									echo "</tr>";
									
							}
						}
						$cn->close($query);
					?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button class="btn btn-warning t-d pull-left" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
				<button class="btn btn-danger t-d" OnClick="savenewadi();"><i class="icon-ok"></i> Aprobar y Guadar</button>
			</div>
		</div>
	</section>
	<div id="fullscreen-icr" class="pull-center">
		<button class="btn btn-danger" onClick="closefull();"><i class="icon-remove"></i></button>
		<iframe id="fullpdf" src="<?php echo $dir; ?>" width="100%" height="90%" frameborder="0">
		</iframe>
	</div>
	<div class="" id="space"></div>
	<footer></footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>