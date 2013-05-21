<?php 
session_start();
include("../datos/postgresHelper.php");
?>
<!DOCTYPE html>
<html lang='es'>
<head>
	<meta charset='utf-8' />
	<title>Comparar Cotizacion</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/int2.css">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<script type="text/javascript" src="../js/verpre.js"></script>
	<script type="text/javascript" src="../ajax/ajxnewnrocompra.js"></script>
	<script src="../modules/mootools-core-1.3.2-full-compat-yc.js"></script>
	<link rel="stylesheet" href="../../web/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../../web/bootstrap/css/bootstrap-responsive.css">
</head>
<body>
	<header>
		<hgroup>
			<img src="../source/icrlogo.png">
			<div id="cab">
				<h1>Especialistas en Sistemas Contra Incendios</h1>
			</div>
		</hgroup>
	</header>
<div id="sess">
<?php
$nom = $_SESSION['nom-icr'];
$car = $_SESSION['car-icr'];
?>
<p>
<label for="user" style="font-weight: bold;">Cargo:</label>
<?echo $car;?>&nbsp;
<label for="nom" style="font-weight: bold;">Nombre: </label>
<?echo $nom;?>
</p>
<p>
<label style="font-weight: bold;">Dni:</label>
&nbsp;<?echo $_SESSION['dni-icr']?>&nbsp;
<label style="font-weight: bold;">User:</label>
<?echo $_SESSION['user-icr'];?>
<button id="btnclose" class="btn btn-mini btn-primary" onclick="javascript:document.location.href = '../../web/includes/session-destroy.php';"> <i class="icon-lock"></i> Cerrar Session</button>
</p>
</div>
<?php if ($_SESSION['accessicr']==true) { ?>
	<section>
		<? include("includes/menu.inc");?>
		<article>
			<span>
				<form name="frmnro" method="POST" action="">
				<label for="nrocot">Ingrese Nro de Cotización: </label>
				<input type="text" maxlength="10" id="txtnro" name="txtnro" title="Ingrese Nro de Cotizacion" placeholder="Nro de Cotizacion" REQUIRED />
				<button class="btn btn-primary" type="Submit"><i class="icon-search icon-white"></i> Ver</button>
				</form>
			</span>
		</article>
		<div id="tbl">
			<br />
			<?php
			$nro = $_POST['txtnro'];
			if (isset($nro)) {
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT DISTINCT d.rucproveedor,p.razonsocial 
											FROM logistica.detcotizacion d INNER JOIN admin.proveedor p 
											ON d.rucproveedor=p.rucproveedor 
											INNER JOIN logistica.cotizacion c
											ON c.nrocotizacion LIKE d.nrocotizacion
											WHERE d.nrocotizacion LIKE '$nro' AND TRIM(c.estado) NOT LIKE '03'");
					if ($cn->num_rows($query)>0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<li>".$result['rucproveedor']."->".$result['razonsocial']."</li>";
						}
					}else{
						echo "<div class='alert alert-warning'>
								<strong>No se han encontrado resultados para su busqueda!</strong>
								</div>";
					}
					$cn->close($query);
					?>
				<table>
					<caption>
                		<details open="open">
                    	<summary></summary>
                    	<p>Comparador de Precio de Proveedores por Precio</p>
               			</details>
            		</caption>
					<thead>	
						<tr>
							<th>Item</th>
							<th>Codigo</th>
							<th id='nom'>Descripcion</th>
							<th id='med'>Medida</th>
							<th>Und</th>
							<th>Cantidad</th>
							<?php
							$tpro = array();
							$j=0;
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT DISTINCT d.rucproveedor 
													FROM logistica.detcotizacion d INNER JOIN admin.proveedor p 
													ON d.rucproveedor=p.rucproveedor
													INNER JOIN logistica.cotizacion c
													ON c.nrocotizacion LIKE d.nrocotizacion
													WHERE d.nrocotizacion LIKE '$nro' AND TRIM(c.estado) NOT LIKE '03'");
							if ($cn->num_rows($query)>0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<th>".$result['rucproveedor']."</th>";
									echo "<th>Editar</th>";
									$tpro[$j] = $result['rucproveedor'];
									$j++;
								}
							}else{

							}
							$cn->close($query);
							?>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 1;
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT * FROM logistica.spconsultartotcant('$nro')");
							if ($cn->num_rows($query)>0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<tr>";
									echo "<td id='cc'>".$i++."</td>";
									echo "<td>".$result['materialesid']."</td>";
									echo "<td id='nom'>".$result['matnom']."</td>";
									echo "<td id='med'>".$result['matmed']."</td>";
									echo "<td id='cc'>".$result['matund']."</td>";
									echo "<td id='cc'>".$result['cantidad']."</td>";
									for($z=0;$z<count($tpro);$z++){
									$cn2 = new PostgreSQL();
									$query2 = $cn2->consulta("SELECT * FROM logistica.spconsultapre('$nro','".$tpro[$z]."','".$result['materialesid']."')");
									if ($cn2->num_rows($query2)>0) {
										while($result2 = $cn2->ExecuteNomQuery($query2)){
											echo "<td id='cc'>".$result2[0]."</td>";
											if ($result2[0] != 0) {
												echo "<td><input type='number' id='".$result['materialesid']."' name='".$tpro[$z]."' value='".$result2[0]."' step='0.01' max='999999' min='0'></td>";
											}else{
												echo "<td style='text-align:center;'> - </td>";
											}
										}
									}
									$cn2->close($query2);
									}
									echo "</tr>";
								}
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="6"></td>
							<?
								echo "<td colspan='".(count($tpro) * 2)."'><hr></td>";
							?>
						</tr>
						<tr>
							<td colspan="6" style="text-align:right; color:#FFF;">Total</td>
							<?
							$pre = array();
							for ($i=0; $i < count($tpro); $i++) {
								$cn = new PostgreSQL();
								$query = $cn->consulta("SELECT SUM(precio) as precio FROM logistica.detcotizacion WHERE nrocotizacion LIKE '$nro' AND rucproveedor LIKE '".$tpro[$i]."'");
								if ($cn->num_rows($query)>0) {
									$result = $cn->ExecuteNomQuery($query);
									echo "<td><label>".$result['precio']."</label></td>";
								}
								$cn->close($query);
								echo "<td><label for='actu' id='ruc".$tpro[$i]."'></label></td>";
							}
							?>
						</tr>
						<tr>
							<td colspan="6"></td>
							<?
							for ($i=0; $i < count($tpro); $i++) {
								echo "<td><button id='".$tpro[$i]."' onclick='actupre(this.id);'>Actualizar</button></td>";
								echo "<td><button id='".$tpro[$i]."' onclick='listamat(this.id);'>Comprar</button></td>";
							}
							?>
						</tr>
					</tfoot>
				</table>
				<hr />
				<?
			}
			?>
		</div>
		<form name="frmcompra" method="POST" action="">
		<div id="compra">
			<label>Orden de Compra</label>
			<hr />
			<table>
			<tr>
			<th><label>Nro Compra: </label></th><th><input type="text" name="nrocom" id="nrocom"  REQUIRED/></th>
			</tr>
			<tr><th><label>Nro Cotizacion: </label></th>
			<th><input  type="text" id="nro" name="nro" value="<?echo $nro;?>"  REQUIRED/>
			<input type="hidden" value='<?echo $nro;?>' id="hnro" /></th></tr>
			<tr>
			<th><label>Ruc Proveedor: </label></th>
			<th><input type="text" id='ruccom' name="ruccom"  REQUIRED /></th></tr>
			<tr>
			<th><label>Lugar de Entraga:</label></th>
			<th><input type="text" id="txtent" name="txtent" value="Jr. Gral. José de San Martin Mz. E Lote 6 Huachipa - Lurigancho Lima" title="Ingrese Lugar a donde se Entregara la Mercancia" placeholder="Lugar de Entrega" REQUIRED /></th></tr>
			<tr>
			<th><label for="doc">Documento: </label></th>
			<th><select id="cbodoc" name="cbodoc" REQUIRED>
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT DISTINCT documentoid,docnom FROM admin.documentos WHERE esid LIKE '%ACT%' ORDER BY docnom ASC");
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<option value='".$result['documentoid']."'>".$result['docnom']."</option>";
					}
				}
				$cn->close($query);
				?>
			</select></th></tr>
			<tr>
			<th><label for="pago">Forma de Pago: </label></th>
			<th><select id="cbopago" name="cbopago" REQUIRED>
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT DISTINCT pagoid,nompag FROM admin.fpago WHERE esid LIKE '%ACT%' ORDER BY nompag ASC");
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<option value='".$result['pagoid']."'>".$result['nompag']."</option>";
					}
				}
				$cn->close($query);
				?>
			</select></th></tr>
			<tr>
			<th><label for="moneda">Moneda: </label></th>
			<th><select id="cbomoneda" name="cbomoneda" REQUIRED>
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT DISTINCT monedaid,nomdes FROM admin.moneda WHERE esid LIKE '10'");
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<option value='".$result['monedaid']."'>".$result['nomdes']."</option>";
					}
				}
				$cn->close($query);
				?>
			</select></th></tr>
			<tr>
			<th><label for="ent">Fecha de Entrega:</label></th>
			<th><input type="date" id="dpent" name="dpent" placeholder="yyyy-MM-dd" title="Ingrese Fecha de Entrega" REQUIRED /></th></tr>
			<tr>
			<th><label for="cont" id="cont">Contacto: </label></th>
			<th><input type="text" id="txtcont" name="txtcont" title="Ingrese el Contacto del Proveedor" /></th></tr>
			<tr>
			<th><label for="cboest">Estado:</label></th>
			<th><select id="cboest" name="cboest" REQUIRED>
				<?php
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT DISTINCT esid,esnom FROM admin.estadoes WHERE estid LIKE '05'");
				if ($cn->num_rows($query)>0) {
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<option value='".$result['esid']."'>".$result['esnom']."</option>";
					}
				}
				$cn->close($query);
				?>
			</select></th></tr>
			</table>
			<input type="Checkbox" id="tod" name="tod" onChange="chkfull(this);" /><label for="tod">Seleccionar Todo</label>
			<div id="dcom"></div>
			<hr />
			<button type="Submit" id="save" name="save" onClick="enviar();"><img src="../source/floppy32.png"></button>
			<button type="Button" onClick="javascript:location.href='compararcot.php'"><img src="../source/cancelar32.png"></button>
		</div>
		</form>
	</section>
	<?}?>
	<div id="textres"></div>
	<div style="margin-top:70px;"></div>
	
<?php
if (isset($_POST['save'])) {
	$nrocom = $_POST['nrocom'];
	$nro = $_POST['nro'];
	$ruccom = $_POST['ruccom'];
	$txtent = $_POST['txtent'];
	$cbodoc = $_POST['cbodoc'];
	$cbopago = $_POST['cbopago'];
	$cbomoneda = $_POST['cbomoneda'];
	$dpent = $_POST['dpent'];
	$txtcont = $_POST['txtcont'];
	$cboest = $_POST['cboest'];
	$chkc = $_POST['chkc'];
	$precio = $_POST['precio'];
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO logistica.compras(nrocompra, rucproveedor, empdni, nrocotizacion, lugent, documentoid,pagoid, monedaid, fecent, contacto, esid) VALUES('$nrocom','$ruccom','".$_SESSION['dni-icr']."','$nro','$txtent','$cbodoc','$cbopago','$cbomoneda',to_date('$dpent','yyyy-MM-dd'),'$txtcont','$cboest')");
	$cn->affected_rows($query);
	$cn->close($query);
}
?>
</body>
</html>