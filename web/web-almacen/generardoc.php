<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect();
	}
?>
<!DOCTYPE html>
<?php
include ("../datos/postgresHelper.php");
?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Aprobar Pedidos</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="css/styleint.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-generardoc.css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="css/styleint-pedido.css">
	<script type="text/javascript" src="js/gendoc.js"></script>
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/styleint-pedido.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script>
		 $(function() {
        	$( "#txtfecha" ).datepicker({ minDate: 0, maxDate: "+1M +10D" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "dd/mm/yy" });
        	$( "#txtfechas" ).datepicker({ minDate: 0, maxDate: "+1M +10D" , changeMonth: true, changeYear: true, showAnim: "slide", dateFormat: "dd/mm/yy" });
        	$('.dropdown-toggle').dropdown();
   		 });
	</script>
</head>
<body>
<?php include("include/menu-al.inc"); ?>
<header>
</header>
<section>
<?php if(!isset($_GET['nrod'])) { ?>
	<article>
		Nro: <?php echo $_GET['nro'];?>
		<br />
		<br>
		<button onclick="gendoc('o','g');" >Guia de Remision</button>
		<button onclick="gendoc('o','n');" >Nota de Salida</button>
	</article>
	<div id="fullscreem">&nbsp;</div>
	<div id="frmnota">
		<h4>Nota de Salida</h4>
		<hr />
		<table>
			<tbody>
				<tr>
					<td><label for="lblnropedido">Nro Pedido:</label></td>
					<td><input id="nrop" name="nrop" type="text" value="<?php echo $_GET['nro'];?>" DISABLED />
						<input type="hidden" id="txtnrop" name="txtnrop" /></td>
				</tr>
				<tr>
					<td><label for="lblfecsal">Fecha Salida:</label></td>
					<td><input type="text" id="txtfecha" name="txtfecha"  placeholder="dd/mm/yyyy" title="Fecha de Salida" /></td>
				</tr>
				<tr>
					<td><label for="lbldes">Destino:</label></td>
					<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("
						SELECT p.direccion,p.ruccliente,c.nombre
						FROM ventas.proyectos p INNER JOIN almacen.pedido e
						ON p.proyectoid = e.proyectoid
						INNER JOIN admin.clientes c
						ON p.ruccliente=c.ruccliente
						WHERE p.esid LIKE '17' AND c.esid LIKE '41' AND e.nropedido LIKE '".$_GET['nro']."'
						");
					$val = "";$ruc = ""; $rz = "";
					if ($cn->num_rows($query)>0) {
						$result = $cn->ExecuteNomQuery($query);
						$val = $result['direccion'];
						$ruc = $result['ruccliente'];
						$rz = $result['nombre'];
					}
					$cn->close($query);
					?>
					<td><input id="txtdestino1" name="txtdestino1" value="<?php echo $val;?>" /></td>
					<td><button class="btn btn-info" onclick="edit('p');"> <i class="icon-pencil"></i> </button></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td><button class="btn btn-warning" onclick="gendoc('c','n');"> <i class="icon-remove"></i></button></td>
					<td><button class="btn btn-info" onclick="generar('n','<?php echo $_GET['nro']?>');"><i class="icon-print"></i></button></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<div id="frmguia">
		<h4>Guia de Remisión</h4>
		<hr />
		<table>
			<thead>
				<tr>
					<td><label for="lblnropedido">Nro Pedido:</label></td>
					<td><input id="nrop" name="nrop" type="text" value="<?php echo $_GET['nro'];?>" DISABLED />
						<input type="hidden" id="txtnrop" name="txtnrop" /></td>
						<td></td>
				</tr>
				<tr>
					<td><label for="lblllegada">Punto de LLegada:</label></td>
					<td><input type="text" id="txtdestino2" name="txtdestino2" value="<?php echo $val;?>" DISABLED /></td>
					<td><button class="btn btn-info" onclick="edit('punto');"> <i class="icon-pencil"></i> </button></td>
				</tr>
				<tr>
					<td><label for="lblruc">R U C:</label></td>
					<td><input type="text" id="txtruc" name="txtruc" value="<?php echo $ruc;?>" maxlength="10" DISABLED /></td>
					<td><button class="btn btn-info" onclick="edit('ruc')"><i class="icon-pencil"></i> </button></td>
				</tr>
				<tr>
					<td><label for="lblrz">Razon Social:</label></td>
					<td><input type="text" id="txtrz" name="txtrz" value="<?php echo $rz;?>" DISABLED /></td>
					<td><button class="btn btn-info" onclick="edit('rz')"> <i class="icon-pencil"></i> </button></td>
				</tr>
				<tr>
					<td><label for="lblfecsal">Fecha Salida:</label></td>
					<td><input type="text" id="txtfechas" name="txtfechas" placeholder="dd/mm/yyyy" title="Fecha de Salida"/></td>
				</tr>
				<tr>
					<td><label for="lblplaca">Transportista:</label></td>
					<td>
						<select id="cbotra" name="cbotra" onclick="trans();">
					<?php
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT traruc,tranom FROM admin.transportista WHERE esid LIKE '43' ");
						if ($cn->num_rows($query)>0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								echo "<option value='".$result['traruc']."'>".$result['tranom']."</option>";
							}
						}
						$cn->close($query);
					?>
					</select>
					</td>
				</tr>
			</thead>
			<tbody id="cbot">
			</tbody>
			<tfoot>
				<tr>
					<td><button class="btn btn-warning" onclick="gendoc('c','g')"><i class="icon-remove"></i></button></td>
					<td><button class="btn btn-info" onclick="generar('g','<?php echo $_GET['nro']?>');"><i class="icon-print"></i></button></td>
				</tr>
			</tfoot>
		</table>
	</div>
<?php } ?>

<?php
if($_REQUEST['nrod'] != ""){
?>
<div id="generator">
<h4>Impresión</h4>
<h6><?php echo $_GET['tdoc']." ".$_GET['nrod'];?></h6>
<hr>
<?php if ($_GET['t'] == "g") { ?>
		<button type="Button" id="cg" class="btn btn-success" onclick="javascript:window.open('../reports/almacen/pdf/rptguiaremision?nro=<?echo $_GET['nrod']?>');" > <img src="../resource/formato32.png" /><br > Con Formato </button>
		<button type="Button" id="sg" class="btn btn-info" onclick="javascript:window.open('../reports/almacen/pdf/rptguiaremisionsin?nro=<?echo $_GET['nrod']?>');"> <img src="../resource/imprimir32.png" /><br >Sin Formato</button>
<?php	}else if($_GET['t']== "n"){ ?>
		<button type="Button" id="cg" class="btn" onclick="javascript:window.open('../reports/almacen/pdf/rptnotasalida?nro=<?echo $_GET['nrod']?>');" > <img src="../resource/formato32.png" /><br > Con Formato </button>
		<button type="Button" id="sg" class="btn" onclick="javascript:window.open('../reports/almacen/pdf/rptnotasalidasin?nro=<?echo $_GET['nrod']?>');"> <img src="../resource/imprimir32.png" /><br >Sin Formato</button>
<?php	} ?>

<hr />
<button type="Button" onclick="javascript:location.href='?';" title="Salir" > <img src="../resource/inicio32.png" /> </button>
</div>
<?php } ?>
</section>
<div style="height: 75px;"></div>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect();
}
?>