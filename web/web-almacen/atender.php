<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(1);
	}

?>
<!DOCTYPE html>
<?php
include ("../datos/postgresHelper.php");
?>
<html lang="es-ES">
<head>
	<meta charset="utf-8" />
	<title>Atender Pedido</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-atender.css">
	<script type="text/javascript" src="js/atender.js"></script>
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedido.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
</head>
<body>
<header>
</header>
<section>
	<?php include("include/menu-al.inc"); ?>
	<div class="container well">
	<hgroup>
		<h4>Atender Pedido Nro : <?php echo $_REQUEST['nro'];?></h4>
	</hgroup>
	<article>
		<?php
		$status = $_REQUEST['es'];
		// recuperando el numero de pedido
		$nrop = $_REQUEST['nro'];
			# realizando una conexion hacia la base de datos
			$cn = new PostgreSQL();
			# ecribiendo el query
			$query = $cn->consulta("SELECT p.proyectoid,p.subproyectoid,p.sector,p.proyectoid,v.descripcion as pronom,p.empdni,e.empnom,
									p.fecha::date,p.fecent,p.obser,l.almacenid,l.descri,s.esnom
									FROM almacen.pedido p INNER JOIN ventas.proyectos v
									ON p.proyectoid = v.proyectoid
									INNER JOIN admin.empleados e
									ON p.empdni = e.empdni
									INNER JOIN admin.almacenes l
									ON p.almacenid = l.almacenid
									INNER JOIN admin.estadoes s
									ON p.esid = s.esid
									WHERE  p.esid LIKE '".$status."' AND p.nropedido LIKE '".$nrop."'
									");

			if ($cn->num_rows($query)>0) {
				while ($result = $cn->ExecuteNomQuery($query)) {
					$pro = $result['proyectoid'];
					$sub = $result['subproyectoid'];
					$sec = $result['sector'];
				?>
				<div class="row show-grid">
					<div class="span6">
						<div class="row show-grid">
							<div class="span3">
								<label for="lblcod"><b>Codigo Proyecto: </b><?php echo $result['proyectoid'];?></label>
								<label for="lblnom"><b>Nombre Proyecto:</b> <?php echo $result['pronom'];?></label>
								<?php
									$cn2 = new PostgreSQL();
									$query2 = $cn2->consulta("SELECT subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$result['proyectoid']."' AND subproyectoid LIKE '".$result['subproyectoid']."'");
									if ($cn2->num_rows($query2)>0) {
										while ($result2 = $cn2->ExecuteNomQuery($query2)) {
											echo "<label for='lblsub'><b>Subproyecto: </b>".$result2['subproyecto']."</label>";
										}
									}
									$cn2->close($query2);

									$cn2 = new PostgreSQL();
									$query2 = $cn2->consulta("SELECT descripcion FROM ventas.sectores WHERE proyectoid LIKE '".$result['proyectoid']."' AND sector LIKE '".$result['sector']."'");
									if ($cn2->num_rows($query2)>0) {
										while ($result2 = $cn2->ExecuteNomQuery($query2)) {
											echo "<label for='lblsub'><b>Sector:</b>".$result2['descripcion']."</label>";
										}
									}
									$cn2->close($query2);

								?>
								<label for="lblemp"><b>Empleado:</b> <?php echo $result['empnom'];?></label>
								<label for="lblfec"><b>Fecha:</b> <?php echo $result['fecha'];?></label>
								<label for="lblent"><b>Fec. Entrega: </b><?php echo $result['fecent'];?></label>
							</div>
							<div class="span3">
								<label for="lblobs"><b>Observacion:</b></label>
								<?php echo $result['obser'];?>
								<label for="lblal"><b>Almacen:</b> <?php echo $result['descri'];?></label>
								<label for="lbles"><b>Estado:</b> <?php echo $result['esnom'];?></label>
							</div>
						</div>
						<div class="btn-group">
							<button type="Button" class="btn btn-warning t-d"onClick="location.href='verpedido.php'"><i class="icon-chevron-left"></i>Atras</button>
							<button type="Button" class="btn btn-info t-d" onclick="atender('<?php echo $nrop;?>');"><i class="icon-shopping-cart"></i> Despachar</button>	
						</div>
					</div>
				</div>
				<img class='img1' src="../resource/cajas.gif">
				<hr>
				<?php
				}
			}
			$cn->close($query);
			# Detalle de Pedido Revisar la existencia  de los materiales
			?>
			<table class="table table-bordered table-condensed">
				<caption><h6 style="text-align: left;">Detalle de Pedido</h6></caption>
				<thead>
					<th>Chk</th>
					<th>Item</th>
					<th>Codigo</th>
					<th>Nombre</th>
					<th>Medida</th>
					<th>Undidad</th>
					<th>Cantidad</th>
					<th>Stock Actual</th>
				</thead>
				<tbody>
					<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT * FROM almacen.sp_consultar_existencia('".$nrop."')");
						if ($cn->num_rows($query)>0) {
							$i = 1;
							$j = 0;
    						$arrni = array();
							while ($result = $cn->ExecuteNomQuery($query)) {
								if ( substr($result['materialesid'], 0,3) == '115') {
									$arrni[$j] = $result['materialesid'];
									$j++;
								}
								if($status == '37'){
									if ($result['auto'] == '1') {
										if ($result['existencia'] < $result['cantidad']) {
											echo "<tr class='nothing'>";
											echo "<td style='text-align:center;'><input type='checkbox' id='".$result['materialesid']."' name='matid' DISABLED /></td>";
										}else{
											echo "<tr>";
											echo "<td style='text-align:center;'><input type='checkbox' id='".$result['materialesid']."' name='matid' CHECKED></td>";
										}
										echo "<td style='text-align:center;'>".$i++."</td>";
										echo "<td>".$result['materialesid']."</td>";
										echo "<td>".$result['matnom']."</td>";
										echo "<td>".$result['matmed']."</td>";
										echo "<td style='text-align:center;'>".$result['matund']."</td>";
										echo "<td style='text-align:center;'>".$result['cantidad']."</td>";
										echo "<td style='text-align:center;'>".$result['existencia']."</td>";
										echo "</tr>";
									}
								}elseif ($status == '35') {
									if ($result['auto'] == '1') {
										if ($result['existencia'] < $result['cantidad']) {
											echo "<tr class='nothing'>";
											echo "<td style='text-align:center;'><input type='checkbox' id='".$result['materialesid']."' name='matid' DISABLED /></td>";
										}else{
											echo "<tr>";
											echo "<td style='text-align:center;'><input type='checkbox' id='".$result['materialesid']."' name='matid' CHECKED></td>";
										}
										echo "<td style='text-align:center;'>".$i++."</td>";
										echo "<td>".$result['materialesid']."</td>";
										echo "<td>".$result['matnom']."</td>";
										echo "<td>".$result['matmed']."</td>";
										echo "<td style='text-align:center;'>".$result['matund']."</td>";
										echo "<td style='text-align:center;'>".$result['cantidad']."</td>";
										echo "<td style='text-align:center;'>".$result['existencia']."</td>";
										echo "</tr>";
									}
								}else{
									echo "<div class='alert alert-warning'>
										<a class='close' data-dismiss='alert'>x</a>
										<b>¡Atención!</b> Esta alerta necesita tu atención, pero no es muy importante.
										<h4>No se encontraron resultados</h4>
										</div>";
								}

							} # end While
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
	</article>
		<div class="row show-grid">
		<div class="span8">
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
						WHERE d.materialesid LIKE '115%' AND d.proyectoid LIKE '".$pro."' AND 
						TRIM(d.subproyectoid) LIKE TRIM('".$sub."') AND TRIM(d.sector) LIKE TRIM('".$sec."')
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
															WHERE proyectoid LIKE '".$pro."' AND TRIM(subproyectoid) LIKE TRIM('".$sub."') 
															AND TRIM(sector) LIKE TRIM('".$sec."') AND materialesid LIKE '".$arrni[$i]."' and flag like '1'");
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
		<div class="span4">
			<div class="well c-green-light">
				<h4 class="t-success">Archivo Adjunto</h4>
				<?php
				$dir = $_SERVER['HTTP_HOST']."/web/project/".$pro."/pedidos/".$_GET['nro'].".pdf";
				//echo $dir;
				if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$pro."/pedidos/".$_GET['nro'].".pdf")) {
					echo "<a href='http://".$dir."' target='_blank'>".$_GET['nro'].".pdf</a>";
				}else{
					echo "<div class='alert alert-error'>";
					echo "<strong>No se ha encontrado el archivo.</strong>";
					echo "</div>";
				}
				?>
			</div>
		</div>
	</div>
	</div>
</section>
<div style="height: 70px;"></div>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect(1);
}
?>