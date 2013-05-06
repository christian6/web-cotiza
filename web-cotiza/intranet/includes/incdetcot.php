<!doctype html>
<?php include("../../datos/postgresHelper.php");

if(isset($_REQUEST['ruc']) && isset($_REQUEST['nro'])){
	$ruc = $_REQUEST['ruc'];
	$nro = $_REQUEST['nro'];
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Detalle Cotización</title>
	<style>
		body{
			background-color: #d5cea6;
			margin: 0 auto;
		}
		header{
			background-color: #cdeb8e;
		}
		#tblh,#tbld{
			border-width: .1em;
			border-spacing: 0;
			font-size: 14px;
			font-weight: normal;
			margin: 0 auto;
			text-align: left;
		}
		#tbld { border-style: solid;}
		#tbld thead tr { background-color: #d5ce00; }
		#tbld thead tr th { padding-left: 1em; padding-right: 1em; padding-top: .5em; padding-bottom: .5em; }
		#tbld tbody tr:hover{
			background-color: #eab92d;
		}
	</style>
</head>
<body>
<header>
<?php 
	$cn = new PostgreSQL();
	$query = $cn->consulta("
		SELECT p.rucproveedor,p.razonsocial,p.direccion,a.paisnom,d.deparnom,r.provnom,i.distnom FROM ".
        "admin.proveedor p INNER JOIN admin.pais a ".
        "ON p.paisid=a.paisid ".
        "INNER JOIN admin.departamento d ".
        "ON p.departamentoid=d.departamentoid ".
        "INNER JOIN admin.provincia r ".
        "ON p.provinciaid=r.provinciaid ".
        "INNER JOIN admin.distrito i ".
        "ON p.distritoid=i.distritoid ".
        "INNER JOIN admin.estadoes e ".
        "ON p.esid=e.esid ".
    	"WHERE a.paisid LIKE d.paisid AND d.departamentoid LIKE r.departamentoid AND r.provinciaid LIKE i.provinciaid AND rucproveedor LIKE '".$ruc."'");
    if ($cn->num_rows($query)) {
    	while ($result = $cn->ExecuteNomQuery($query)) {
?>
	<table id="tblh">
		<thead>
			<tr>
				<th>Ruc:</th>
				<th><?php echo $result['rucproveedor'];?></th>
				<th>Dirección:</th>
				<th><?php echo $result['direccion'];?></th>
				<th>Provincia:</th>
				<th><?php echo $result['provnom'];?></th>
			</tr>
			<tr>
				<th>Razón Social:</th>
				<th><?php echo $result['razonsocial'];?></th>
				<th>Distrito:</th>
				<th><?php echo $result['distnom'];?></th>
				<th>Departamento:</th>
				<th><?php echo $result['deparnom'];?></th>
			</tr>
<?php
    	}
    }
    $cn->close($query);

    $cn = new PostgreSQL();
    $query = $cn->consulta("
    SELECT c.tentr,c.contacto,c.fval,m.nomdes,c.cotori
    FROM logistica.cotizacioncli c INNER JOIN admin.moneda m
    ON m.monedaid=c.monedaid
    WHERE c.nrocotizacion LIKE '".$nro."' AND c.rucproveedor LIKE '".$ruc."'
    ORDER BY c.fecreg DESC Limit 1 OFFSET 0
    ");
  	if ($cn->num_rows($query)>0) {
  		$pc = "../../fcotizacion/";
    	while ($result =  $cn->ExecuteNomQuery($query)) {
?>
			<tr>
				<th>Tiempo de Entrega:</th>
				<th><?php echo $result['tentr'];?></th>
				<th>Tiempo de Oferta:</th>
				<th><?php echo $result['fval'];?></th>
			</tr>
			<tr>
				<th>Contacto:</th>
				<th><?php echo $result['contacto'];?></th>
				<th>Moneda:</th>
				<th><?php echo $result['nomdes'];?></th>
			</tr>
			<tr>
				<th>Adjunto:</th>
				<th><a href="<?php echo $pc.$result['cotori'];?>" target='_blank' ><img src="../../source/pdf.png"></a></th>
			</tr>
<?php
    	}
	}
	$cn->close($query);
?>
	</thead>
	</table>
</header>
<section>
<?php
$cn = new PostgreSQL();
$sub = 0;
$query = $cn->consulta("SELECT * FROM logistica.spcondetcotizapro('".$nro."','".$ruc."')");
  if ($cn->num_rows($query)>0) {
    $i = 1;
?>
<table id="tbld">
	<legend>Detalle de Cotización</legend>
	<thead>
		<tr>
			<th>Item</th>
			<th>Catalogo</th>
			<th>Codigo</th>
			<th>Descripción</th>
			<th>Medida</th>
			<th>Unidad</th>
			<th>Cantidad</th>
			<th>Precio</th>
			<th>Importe</th>
		</tr>
	</thead>
	<tbody>
<?php
$dir = "../../catalogos/";
 while($fila = $cn->ExecuteNomQuery($query)){
     // $pdf->Row(array($fila['materialesid'],$fila['matnom'], $fila['matmed'], $fila['matund'], $fila['cantidad'],$fila['precio'],$fila['importe']));
echo "<tr>";
echo "<td style='text-align: center;'>".$i++."</td>";
if (is_file($dir.$fila['materialesid'].".pdf")){
	echo "<td style='text-align: center;'><a href='".$dir.$fila['materialesid'].".pdf' target='_blank' ><img src='../../source/pdf.png' /></a></td>";
}else{
	echo "<td style='text-align: center;'><img src='../../source/no16.png' /></td>";
}
echo "<td>".$fila['materialesid']."</td>";
echo "<td>".$fila['matnom']."</td>";
echo "<td>".$fila['matmed']."</td>";
echo "<td style='text-align: center;'>".$fila['matund']."</td>";
echo "<td style='text-align: center;'>".$fila['cantidad']."</td>";
echo "<td style='text-align: right;'>".$fila['precio']."</td>";
echo "<td style='text-align: right;'>".$fila['importe']."</td>";
echo "</tr>";
}
}
?>
	</tbody>
</table>
</section>
</body>
</html>
<?php
}else{

}
?>