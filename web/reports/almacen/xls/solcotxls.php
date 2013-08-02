<?php
require("../../datos/postgresHelper.php");
$ruc = $_GET['ruc'];
$nro = $_GET['nro'];
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=$nro.xls");
?>
<html lang='es'>
<head>
	<meta charset="utf-8" />
	<title>xls Cotizacion</title>
</head>
<body>
	<header>
		<h2 style="color:red;">ICR PERU</h2>
		<center><h4><p>Jr. Gral. Jose de San Martin Mz. E Lote 6 Huachipa - Lurigancho Lima 15, Peru
			<br>Central Telefonica: (511) 371-0443
			<br>E-mail: logistica@icrperusa.com
		</p></h4></center>
		<br>
		<h3>Solicitud de Cotizacion</h3>
		<h4>Nro <?echo $nro;?></h4><br>
		<?php
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT rucproveedor,razonsocial,direccion,distrito,provincia,departamento,pais FROM admin.proveedor WHERE rucproveedor LIKE '".$ruc."'");
	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			?>
			<label><b>RUC:</b> </label><?echo $result['rucproveedor'];?><br>
			<label><b>Razon Social:</b> </label><?echo $result['razonsocial'];?><br>
			<label><b>Direccion:</b> </label><?echo $result['direccion'];?><br>
			<label><b>Distrito:</b> </label><?echo $result['distrito'];?><br>
			<label><b>Provincia:</b> </label><?echo $result['provincia'];?><br>
			<label><b>Departamento:</b> </label><?echo $result['departamento'];?><br>
			<label><b>Pais:</b> </label><?echo $result['pais'];?><br>
			<?
		}
	}
	$cn->close($query);
	?>
	<hr />
		<h6>Estimados señores:<br />
		Por la presente nos es grato hacerles llegar nuestra solicitud de cotización por el siguiente material.</h6>
	<hr />
	</header>
	<section>
		<center>
			<table>
				<thead>
					<tr style="background-color:#8B0000; color:#FFFFFF;">
						<th>Item</th>
						<th>Descripcion</th>
						<th>Medida</th>
						<th>Unidad</th>
						<th>Cantidad</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT * FROM logistica.spconsultardetcotizacion('".$nro."','".$ruc."')");
					if ($cn->num_rows($query)>0) {
					$i = 1;
					while($fila = $cn->ExecuteNomQuery($query)){
						echo "<tr>";
						echo "<td style='background-color:#FFFFE0; text-align:center;'>".$i++."</td>";
						echo "<td style='background-color:#FFFFE0;'>".$fila['matnom']."</td>";
						echo "<td style='background-color:#FFFFE0;'>".$fila['matmed']."</td>";
						echo "<td style='background-color:#FFFFE0; text-align:center;'>".$fila['matund']."</td>";
						echo "<td style='background-color:#FFFFE0; text-align:center;'>".$fila['cantidad']."</td>";
						echo "</tr>";
    				}
      				$cn->close($query);
    				}
					?>
				</tbody>
			</table>
			<hr />
		</center>
	</section>
	<footer>
	</footer>
</body>
</html>