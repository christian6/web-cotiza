<!DOCTYPE html>
<?php
session_start();

include ("../datos/postgresHelper.php");

if (isset($_POST['btnsa'])) {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM admin.spnuevomoneda()");
	if ($cn->num_rows($query)) {
		$result = $cn->ExecuteNomQuery($query);
		$cn2 = new PostgreSQL();
		$query2 = $cn2->consulta("INSERT INTO admin.moneda VALUES('$result[0]','".$_POST['txtmo']."','10','".$_POST['txtsim']."')");
		$cn2->affected_rows($query2);
		$cn2->close($query2);
	}
	$cn->close($query);
}

?>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Mantenimiento Moneda</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../css/intranet/style-moneda.css">
	<script type="text/javascript" src="../js/intranet/moneda.js"></script>
	<script type="text/javascript" src="../ajax/intranet/ajxmoneda.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="../modules/styletable.jquery.plugin.js"></script>
<script>  
    $(document).ready(function(){  
       $('table').styleTable({  
    th_bgcolor: '#CDDFB5',  
    th_border_color: '#4C5F3B',  
    tr_odd_bgcolor: '#F2FFE1',  
    tr_even_bgcolor: '#ffffff',  
    tr_border_color: '#6E8F50',  
    tr_hover_bgcolor: '#B4CF9B'  
}); 
    });  
</script>
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
<?php 
if($_SESSION['accessicr']==true) { ?>
	<section>
		<?php include("includes/menu.inc");?>
		<hgroup>
			<h3>Mantenimientos Moneda</h3>
		</hgroup>
		<div id="cont">
			<form method="POST" action="">
		<span>
			<button type="Button" id="btnplus" OnClick="status('t');"><img src="../source/plus32.png"></button>
			<button type="Button" id="btncan" OnClick="status('f');"><img src="../source/cancelar32.png"></button>
			<button type="Submit" id="btnsa" name="btnsa" DISABLED ><img src="../source/floppy32.png"></button>
		</span>
		<br />
		<br />
		<label for="nom">Moneda:</label>
		<input id="txtmo" name="txtmo" title="Descripción moneda" placeholder="Moneda" REQUIRED DISABLED />
		<br />
		<label for="sim">Simbolo:</label>
		<input id="txtsim" name="txtsim" title="Simbolo de moneda" placeholder="Simbolo" DISABLED />
			</form>
			<br>
			<table border="0" cellpadding="4" cellspacing="0" align="center" width="30%">
				<thead>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Simbolo</th>
					<th>Estado</th>
					<th>Modificar</th>
					<th>Eliminar</th>
				</thead>
				<tbody>
						<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT m.monedaid,m.nomdes,m.simbolo,e.esnom FROM admin.moneda m INNER JOIN admin.estadoes e ON m.esid=e.esid ORDER BY m.monedaid ASC");
					if ($cn->num_rows($query)) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<tr>";
							?>
							<td style='text-align:center'><?echo $result['monedaid']?></td>
							<td><input type='text' id="n<? echo $result['monedaid'];?>" name='txtmo' value="<? echo $result['nomdes'];?>"></td>
							<td><input type='text' id="s<? echo $result['monedaid'];?>" name='txtsim' style='width:30px;' value="<?echo $result['simbolo'];?>"></td>
							<td>
								<select id="cbo<? echo $result['monedaid']?>">
									<?php
									$cn3 = new PostgreSQL();
									$query3 = $cn3->consulta("SELECT esid,esnom FROM admin.estadoes WHERE estid LIKE '08'");
									if ($cn3->num_rows($query3)>0) {
										while ($result3 = $cn3->ExecuteNomQuery($query3)) {
											if($result['esnom'] == $result3['esnom']){
												echo "<option value='".$result3['esid']."' SELECTED>".$result3['esnom']."</option>";
											}else{
												echo "<option value='".$result3['esid']."'>".$result3['esnom']."</option>";
											}
										}
									}
									$cn3->close($query3);
									?>
								</select>
							</td>
							<td style='text-align:center'><a href='javascript:updatemoneda("<?echo $result['monedaid'];?>");'><img src='../source/editar16.png' /></a></td>
							<td style='text-align:center'><a href='javascript:deletemoneda("<?echo $result['monedaid'];?>");'><img src='../source/delete.png' /></a></td>
							<?
							echo "</tr>";
						}
					}
					$cn->close($query);
					?>
				</tbody>
			</table>
		</div>
	</section>
<?php
}
?>
	<footer>
	</footer>
</body>
</html>