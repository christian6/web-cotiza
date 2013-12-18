<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(1);
	}
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Recibir Orden de Compra</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../modules/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="../modules/jquery1.9.js"></script>
	<script src="../modules/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/ingxcompra.js"></script>
	<style>
		@import url('http://fonts.googleapis.com/css?family=Noto+Sans');

		.container .control-group label { font-size: 12px; margin-top: 0em; margin-bottom: 0em; padding-left: 1em; }
		#mtd2 .modal-body label, #nronota { font-weight: bold; }
		.table tbody tr td { font-family: 'Noto Sans', sans-serif; font-size: 12px; }
		#tl{ text-align: center; }
	</style>
</head>
<body>
	<?php include("include/menu-al.inc"); ?>
	<header>
	</header>
	<section>
		<div class="container well">
			<?php if ($_REQUEST['nro'] != ''){ ?>
				<h4>Recibir Orden de Compra</h4>
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT c.nrocompra,c.rucproveedor,p.razonsocial,c.fecha::date,c.fecent,e.esnom
											FROM logistica.compras c INNER JOIN admin.proveedor p
											ON c.rucproveedor = p.rucproveedor
											INNER JOIN admin.estadoes e
											ON c.esid = e.esid
											WHERE c.nrocompra LIKE '".$_REQUEST['nro']."'
											");
					$ruc ='';$rz='';$fe='';$es='';
					if ($cn->num_rows($query) > 0) {
						$result = $cn->ExecuteNomQuery($query);
						$ruc = $result['rucproveedor'];
						$rz = $result['razonsocial'];
						$fe = $result['fecent'];
						$es = $result['esnom'];
				?>
				<div class="container">
					<div class="control-group">
						<label for="label"><strong>Nro de Orden de Compra:</strong> <?php echo $result['nrocompra']; ?></label>
						<label for="label"><strong>Ruc Proveedor:</strong> <?php echo $result['rucproveedor']; ?></label>
						<label for="label"><strong>Razon Social:</strong> <?php echo $result['razonsocial']; ?></label>
						<label for="label"><strong>Fec. Registrado:</strong> <?php echo $result['fecha']; ?></label>
						<label for="label"><strong>Fec. Entrega:</strong> <?php echo $result['fecent']; ?></label>
						<label for="label"><strong>Estado:</strong> <?php echo $result['esnom']; ?></label>
						<br>
						<div class="controls">
							<button id="btnrecibirc" class="btn btn-primary" OnClick="recibirmat();"><i class="icon-th-large icon-white"></i> Recibir Compra</button>
							<button class="btn btn-warning" OnClick="location.href='ingresoxcompra.php'"><i class="icon-hand-left icon-white"></i> Lista de Compras</button>
						</div>
					</div>
				</div>
				<br>
			<?php 

					}
				?>	
			<table class="table table-bordered table-striped table-hover">
				<caption>
					<div class="controls">
						<label class="checkbox label label-success"><input type="checkbox" name="chkf" id="chkf" OnChange="chkdetmatfull();" CHECKED /> Seleccionar todo.</label>

					</div>
				</caption>
				<thead>
					<tr>
						<th>Check</th>
						<th>Item</th>
						<th>Codigo</th>
						<th>Descripcion</th>
						<th>Medida</th>
						<th>Und</th>
						<th>Cantidad</th>
						<th>Cantidad Rec.</th>
						<th>Fecha Rec.</th>
						<th>Precio</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$cn = new PostgreSQL();
						/// en la consulta se lista los materiales que estan en flag 0 o flag 1 respecivamente; 0 si es la primera vez y 1 si ya se re
						$query = $cn->consulta("SELECT d.materialesid,m.matnom,m.matmed,m.matund,d.cantidad,d.cantstatic,d.precio,(d.cantstatic * d.precio)as total
												FROM logistica.compras c INNER JOIN logistica.detcompras d
												ON c.nrocompra LIKE d.nrocompra
												INNER JOIN admin.materiales m
												ON d.materialesid LIKE m.materialesid
												WHERE d.flag NOT LIKE '2' AND c.nrocompra LIKE '".$_REQUEST['nro']."'
												");
						if ($cn->num_rows($query) > 0) {
							$i = 1;
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<tr>";
								echo "<td id='tl'>"."<input type='checkbox' name='matid' id='".$result['materialesid']."' value='".$i."' OnChange='change(this);' Checked />"."</td>";
								echo "<td id='tl'>".$i."</td>";
								echo "<td>".$result['materialesid']."</td>";
								echo "<td>".$result['matnom']."</td>";
								echo "<td>".$result['matmed']."</td>";
								echo "<td id='tl'>".$result['matund']."</td>";
								echo "<td id='tl'>".$result['cantidad']."</td>";
								echo "<td><input type='number' id='cant".$i."' name='cants' class='input-small' value='".$result['cantidad']."' max='".$result['cantstatic']."' min='0' OnBlur='valc(".$result['cantstatic'].",this);' ></td>";
								echo "<td><input type='text' id='fec".$i."' name='fecs' value='".date("Y-m-d")."' class='input-small'></td>";
								echo "<td id='tl'>".$result['precio']."<input type='hidden' id='pre".$i."' name='precios' value='".$result['precio']."'></td>";
								echo "<td id='tl'>".$result['total']."</td>";
								echo "</tr>";
								$i++;
							}
						}
					?>
				</tbody>
			</table>
			<?php 
				} 
			?>
		</div>
		<div id="mtd" class="modal hide fade in">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
            	<h5>Generar Nota de Ingresos</h5>
            	<caption>
            		<center>
            			<span class="label label-info">1</span>
            			<span class="label">2</span>
            			<span class="label">3</span>
            		</center>
            	</caption>
			</div>
			<div class="modal-body">
				<label class="label label-info"><b>Nro Orden de Compra </b></label>
				<label><?php echo $_REQUEST['nro']; ?></label>
				<label class="label label-info"><b>Ruc Proveedor</b></label>
				<label><?php echo $ruc; ?></label>
				<input type="hidden" id="txtrucpro" name="txtrucpro" value="<?php echo $ruc; ?>" />
				<label class="label label-info"><b>Razón Social</b></label>
				<label><?php echo $rz; ?></label>
				<label class="label label-info"><b>Fecha de Entrega</b></label>
				<label><?php echo $fe; ?></label>
				<label class='label label-info'><b>Almancen</b></label>
				<div class="controls">
				<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT m.almacenid,a.descri FROM almacen.sumcot s INNER JOIN logistica.compras c
											ON s.nrocotizacion LIKE c.nrocotizacion
											INNER JOIN almacen.suministro m
											ON s.nrosuministro LIKE m.nrosuministro
											INNER JOIN admin.almacenes a
											ON m.almacenid LIKE a.almacenid
											WHERE c.nrocompra LIKE '".$_REQUEST['nro']."' ");
					if ($cn->num_rows($query) > 0) {
						$result = $cn->ExecuteNomQuery($query);
						echo "<label>".$result['descri']."</label>";
						echo "<input type='hidden' id='alid' name='alid' value='".$result['almacenid']."'>";
					}else{
						$c = new PostgreSQL();
						$q = $c->consulta("SELECT almacenid,descri FROM ADMIN.ALMACENES WHERE esid LIKE '21'");
						if ($c->num_rows($q) > 0) {
							echo "<select id='cboal' name='cboal'>";
							while ($r = $c->ExecuteNomQuery($q)) {
								echo "<option value='".$r['almacenid']."'>".$r['descri']."</option>";
							}
							echo "</select>";
						}
						$c->close($query);
					}
					$cn->close($query);
				?>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Cerrar</a>
              	<a href="javascript:next(2);" class="btn btn-primary">Siguiente<i class="icon-chevron-right icon-white"></i></a>
			</div>
		</div>
		<div id="mtd2" class="modal hide fade in">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
            	<h5>Generar Nota de Ingresos</h5>
            	<caption>
            		<center>
            			<span class="label">1</span>
            			<span class="label label-info">2</span>
            			<span class="label">3</span>
            		</center>
            	</caption>
			</div>
			<div class="modal-body">
				<div class="row show-grid">
					<div class="span5">
						<div class="row show-grid">
							<div class="span4">
								<div class="control-group">
									<label class="label label-info">Nro Guia:</label>
									<div class="controls">
										<input type="text" class="span2" id="txtnrog" maxlength="12" placeholder="Nro Guia Remision" title="Ingrese Nro Guia Remision" />
										<input type="hidden" id="ncom" name="ncom"  maxlength="12" value="<?php echo $_REQUEST['nro']; ?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="label label-info">Nro Cotizacion</label>
									<div class="controls">
										<?php
											$cn = new PostgreSQL();
											$query = $cn->consulta("SELECT nrocotizacion FROM logistica.compras WHERE nrocompra LIKE '".$_REQUEST['nro']."'");
											if ($cn->num_rows($query) > 0) {
												$result = $cn->ExecuteNomQuery($query);
												$nc = $result['nrocotizacion'];
											}
											$cn->close($query);
										?>
										<input type="text" class="span2" id="txtnroc" name="txtnroc" value="<?php echo $nc; ?>" DISABLED>
									</div>
								</div>
								<div class="control-group">
									<label class="label label-info">Nro Factura</label>
									<div class="controls">
										<input type="text" class="span2" id='txtnrof' name="txtnrof" maxlength="12" placeholder="Nro Factura" title="Ingrese Nro Factura" >
									</div>
								</div>
								<div class="control-group">
									<label class="label label-info">Movito</label>
									<div class="controls">
										<input type="text" class="span4" id="txtmot" name="txtmot" placeholder="Motivo del Ingreso" title="Ingrese Motivo">
									</div>
								</div>
								<div class="control-group">
									<label class="label label-info">Observación</label>
									<div class="controls">
										<textarea id="txtobser" name="txtobser" placehodel="Observación"></textarea>
									</div>
								</div>
								<div class="control-group">
									<label class="label label-info">Realizado</label>
									<div class="controls">
										<input type="text" class="input-small inline" id="txtr" name="txtr" value="<?php echo $_SESSION['dni-icr']; ?>" DISABLED />
										<input type="text" class="span3" id="txtrn" name="txtrn" value="<?php echo $_SESSION['nom-icr']; ?>" title="Realizado por" DISABLED />
									</div>
								</div>
								<div class="control-group">
									<label class="label label-info">Recibido</label>
									<div class="controls">
										<select id="cbore" name="cbore">
											<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT empdni,empnom 
																		FROM admin.empleados e INNER JOIN admin.cargo c
																		ON e.cargoid=c.cargoid
																		WHERE e.cargoid = 4
																		");
												if ($cn->num_rows($query) > 0) {
													while ($result = $cn->ExecuteNomQuery($query)) {
														echo "<option value='".$result['empdni']."'>".$result['empnom']."</option>";
													}
												}
												$cn->close($query);
											?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="label label-info">Inspeccionado</label>
									<div class="controls">
										<select id="cboins" name="cboins">
											<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT empdni,empnom 
																		FROM admin.empleados e INNER JOIN admin.cargo c
																		ON e.cargoid=c.cargoid
																		WHERE e.cargoid = 4
																		");
												if ($cn->num_rows($query) > 0) {
													while ($result = $cn->ExecuteNomQuery($query)) {
														echo "<option value='".$result['empdni']."'>".$result['empnom']."</option>";
													}
												}
												$cn->close($query);
											?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="label label-info">V.B.</label>
									<div class="controls">
										<select id="cbovb" name="cbovb">
											<?php
												$cn = new PostgreSQL();
												$query = $cn->consulta("SELECT empdni,empnom 
																		FROM admin.empleados e INNER JOIN admin.cargo c
																		ON e.cargoid=c.cargoid
																		WHERE e.cargoid = 4
																		");
												if ($cn->num_rows($query) > 0) {
													while ($result = $cn->ExecuteNomQuery($query)) {
														echo "<option value='".$result['empdni']."'>".$result['empnom']."</option>";
													}
												}
												$cn->close($query);
											?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="javascript:back(2);" class="btn"><i class='icon-chevron-left'></i> Antras</a>
              	<a href="javascript:valid();" class="btn btn-primary">Guardar y Seguir <i class="icon-chevron-right icon-white"></i></a>
			</div>
		</div>
		<div id="mtd3" class="modal hide fade in">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
            	<h5>Generar Nota de Ingresos</h5>
            	<caption>
            		<center>
            			<span class="label">1</span>
            			<span class="label">2</span>
            			<span class="label label-success">3</span>
            		</center>
            	</caption>
			</div>
			<div class="modal-body">
				<div class="alert alert-success">
					<!--<a class="close" data-dismiss="alert">×</a>-->
					<strong>Bien hecho!</strong> Se ha guardado correctamente la nota de Ingreso.
					<p>
						El número de la nota es: <label class="help-inline" id="nronota"></label>
					</p>
				</div>
				<h5>Que desea hacer con la nota de Ingreso?</h5>
				<hr>
				<center>
					<button type="Button" class="btn btn-primary" OnClick="printview();"><i class="icon-print icon-white"></i> Imprimir</button>
					<button type="Button" class="btn btn-success" OnClick="location.href='ingresoxcompra.php'"><i class="icon-th icon-white"></i> Más Compras</button>
					<button type="Button" class="btn" OnClick="report();"><i class="icon-list"></i> Reporte de Inspección</button>
				</center>
				<hr>
			</div>
			<div class="modal-footer">
				<!--<a href="javascript:back(3);" class="btn"><i class='icon-chevron-left'></i> Antras</a>-->
              	<a href="javascript:location.href='ingresoxcompra.php'" class="btn btn-primary">Terminar</a>
			</div>
		</div>
	</section>
	<div id="space">
	</div>
	<footer>
		<!--<button onClick="next(3)">Click me</button>-->
	</footer>
</body>
</html>
<?php
}else{
	redirect(1);
}
?>