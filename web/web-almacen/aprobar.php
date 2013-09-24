<!DOCTYPE html>
<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('sk') == 0) {
		redirect();
	}
	include ("../datos/postgresHelper.php");
?>
<html lang="es">
<head>
	<meta charset="uft-8" />
	<title>Aprobar Pedido</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedidoes.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="js/aprobar.js"></script>
</head>
<body>
<header>
</header>
<section>
<?php include("include/menu-al.inc"); ?>
	<div class="container well c-blue-light">
	<hgroup>
		<h4>Pedido al Almacen Nro: <span id="lblnro"><?php echo TRIM($_GET['nro']);?></span></h4>
	</hgroup>
	<article>
		<?php
		// recuperando el numero de pedido
		$nrop = $_GET['nro'];
		if (isset($nrop)) {
			# realizando una conexion hacia la base de datos
			$cn = new PostgreSQL();
			# ecribiendo el query
			$query = $cn->consulta("SELECT p.proyectoid,p.subproyectoid,p.sector,r.descripcion as pronom,p.empdni,e.empnom,p.fecha::date,p.fecent,p.obser,p.almacenid,a.descri,s.esnom ".
									"FROM almacen.pedido p INNER JOIN ventas.proyectos r ".
									"ON p.proyectoid=r.proyectoid ".
									"INNER JOIN admin.empleados e ".
									"ON p.empdni=e.empdni ".
									"INNER JOIN admin.almacenes a ".
									"ON p.almacenid=a.almacenid ".
									"INNER JOIN admin.estadoes s ".
									"ON p.esid=s.esid ".
									"WHERE p.nropedido LIKE '".$nrop."' AND p.esid LIKE '32'");

			if ($cn->num_rows($query)>0) {
				while ($result = $cn->ExecuteNomQuery($query)) {
				?>
				<div class="row show-grid">
					<div class="span6 well c-green-light t-info">
						<div class="row show-grid">
							<div class="span3">
								<label for="lblcod"><b>Codigo Proyecto:</b> <?php echo $result['proyectoid'];?></label>
								<label for="lblnom"><b>Nombre de Proyecto:</b> <?php echo $result['pronom'];?></label>
							<?php
							$pro = $result['proyectoid'];
							$sub = $result['subproyectoid'];
							$sec = $result['sector'];
							$cn2 = new PostgreSQL();
							$query2 = $cn2->consulta("SELECT subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$result['proyectoid']."' AND subproyectoid LIKE '".$result['subproyectoid']."'");
							if ($cn2->num_rows($query2)>0) {
								while ($result2 = $cn2->ExecuteNomQuery($query2)) {
									echo "<label for='lblsub'><b>Subproyecto:</b> ".$result2['subproyecto']."</label>";
								}
							}
							$cn2->close($query2);

							$cn2 = new PostgreSQL();
							$query2 = $cn2->consulta("SELECT descripcion FROM ventas.sectores WHERE proyectoid LIKE '".$result['proyectoid']."' AND sector LIKE '".$result['sector']."'");
							if ($cn2->num_rows($query2)>0) {
								while ($result2 = $cn2->ExecuteNomQuery($query2)) {
									echo "<label for='lblsub'><b>Sector:</b> ".$result2['descripcion']."</label>";
								}
							}
							$cn2->close($query2);

							?>
								<label for="lblemp"><b>Empleado:</b> <?php echo $result['empnom'];?></label>
								<label for="lblfec"><b>Fecha:</b> <?php echo $result['fecha'];?></label>
								<label for="lblent"><b>Fec. Entrega:</b> <?php echo $result['fecent'];?></label>
							</div>
							<div class="span3">
								<label for="lblobs"><b>Observacion:</b></label>
								<?php echo $result['obser'];?>
								<label for="lblal"><b>Almacen:</b> <?php echo $result['descri'];?></label>
								<label for="lbles"><b>Estado:</b> <?php echo $result['esnom'];?></label>
						</div>
							</div>
							<div class="btn-group">
								<button type="Button" class="btn btn-primary" onclick="aprobar('<?php echo $nrop;?>');" title="Aprobar Pedido"><i class="icon-ok icon-white"></i> Aprobar</button>
								<button type="Button" class="btn btn-warning t-d" onclick="showmobs();" title="Anular Pedido"><i class="icon-remove"></i> Anular</button>
								<a href="aprobarpedido.php" class="btn"><i class="icon-arrow-left"></i> Volver</a>
							</div>							
						</div>
					</div>
				<?php
				}
			}
			$cn->close($query);

			# Detalle de Pedido
			?>
			<div class="cont">
			<table class="table table-striped table-bordered table-condensed">
				<caption><h5 style="text-align: left;">Detalle de Pedido</h5></caption>
				<thead>
					<tr>
						<th></th>
						<th>Codigo</th>
						<th>Descripción</th>
						<th>Medida</th>
						<th>Unidad</th>
						<th>Cantidad</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT * FROM almacen.spconsultardetpedidomat('".$nrop."')");
  					if ($cn->num_rows($query)>0) {
    					$i = 1;
    					$j = 0;
    					$arrni = array();
    					while($fila = $cn->ExecuteNomQuery($query)){
    						if ( substr($fila['materialesid'], 0,3) == '115') {
								$arrni[$j] = $fila['materialesid'];
								$j++;
							}
    						echo "<tr>";
    						echo "<td style='text-align: center;'>".$i++."</td>";
    						echo "<td>".$fila['materialesid']."</td>";
    						echo "<td>".$fila['matnom']."</td>";
    						echo "<td>".$fila['matmed']."</td>";
    						echo "<td style='text-align: center;'>".$fila['matund']."</td>";
    						echo "<td style='text-align: center;'>".$fila['cantidad']."</td>";
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
					?>
				</tbody>
			</table>
			</div>
			<?php
		}
		?>
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
	</div>
	</div>
	<div id="manular" class="modal fade in c-red-light t-error hide">
		<div class="modal-header">
			<a data-dismiss="modal" class="close">&times;</a>
			<h4>Por que estas anulando el pedido?</h4>
		</div>
		<div class="modal-body">
			<div class="row show-grid">
				<div class="span5">
					<div class="control-group">
						<label for="controls" class="control-label">Ingrese su observación</label>
						<div class="controls">
							<textarea name="obsa" id="obsa" class='span5' rows="5"></textarea>
						</div>
					</div>
				</div>
				<div class="span5">
					<button class="btn t-d" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
					<button class="btn btn-danger pull-right t-d" onclick="anular();"><i class="icon-ok"></i> Anular</button>
				</div>
			</div>
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
	redirect();
}
?>