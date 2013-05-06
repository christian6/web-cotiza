<?php
include ("../../datos/postgresHelper.php");

$type = $_REQUEST['tip'];
$cod = $_REQUEST['cod'];
$cant = $_REQUEST['cant'];

if (isset($type)) {
if ($type == "add") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO logistica.tmpcotiza VALUES('$cod',$cant)");
	$cn->affected_rows($query);
	$cn->close();
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT t.materialesid,m.matnom,m.matmed,m.matund,t.cantidad FROM admin.materiales m INNER JOIN logistica.tmpcotiza t on m.materialesid like t.materialesid");
	if ($cn->num_rows($query)>0) {
?>
<table>
<caption>Detalle de Materiales</caption>
<thead>
<tr id="tcab">
<th>Codigo</th>
<th>Descripcion</th>
<th>Medida</th>
<th>Unidad</th>
<th>Cantidad</th>
<th>Eliminar</th>
</tr>
<?
		while ($result = $cn->ExecuteNomQuery($query)) {
?>
<tr>
<td><?echo $result['materialesid'];?></td>
<td><?echo $result['matnom'];?></td>
<td><?echo $result['matmed'];?></td>
<td><?echo $result['matund'];?></td>
<td><?echo $result['cantidad'];?></td>
<td><a id="del" href="javascript:grilla(<?echo $result['materialesid'];?>)"></a></td>
</tr>
<?
		}
?>
</thead>
</table>
<?
	}
	$cn->close($query);
}else if($type == "del"){
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM logistica.tmpcotiza WHERE materialesid like '$cod'");
	$cn->affected_rows($query);
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT t.materialesid,m.matnom,m.matmed,m.matund,t.cantidad FROM admin.materiales m INNER JOIN logistica.tmpcotiza t on m.materialesid like t.materialesid");
	if ($cn->num_rows($query)>0) {
?>
<table>
<caption>Detalle de Materiales</caption>
<thead>
<tr id="tcab">
<th>Codigo</th>
<th>Descripcion</th>
<th>Medida</th>
<th>Unidad</th>
<th>Cantidad</th>
<th>Eliminar</th>
</tr>
<?
		while ($result = $cn->ExecuteNomQuery($query)) {
?>
<tr>
<td><?echo $result['materialesid'];?></td>
<td><?echo $result['matnom'];?></td>
<td><?echo $result['matmed'];?></td>
<td><?echo $result['matund'];?></td>
<td><?echo $result['cantidad'];?></td>
<td><a id="del" href="javascript:grilla(<?echo $result['materialesid'];?>)"></a></td>
</tr>
<?
		}
?>
</thead>
</table>
<?
	}
	$cn->close($query);
}else if($type == "lista"){
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT t.materialesid,m.matnom,m.matmed,m.matund,t.cantidad FROM admin.materiales m INNER JOIN logistica.tmpcotiza t on m.materialesid like t.materialesid");
	if ($cn->num_rows($query)>0) {
?>
<table>
<caption>Detalle de Materiales</caption>
<thead>
<tr id="tcab">
<th>Codigo</th>
<th>Descripcion</th>
<th>Medida</th>
<th>Unidad</th>
<th>Cantidad</th>
<th>Eliminar</th>
</tr>
<?
		while ($result = $cn->ExecuteNomQuery($query)) {
?>
<tr>
<td><?echo $result['materialesid'];?></td>
<td><?echo $result['matnom'];?></td>
<td><?echo $result['matmed'];?></td>
<td><?echo $result['matund'];?></td>
<td><?echo $result['cantidad'];?></td>
<td><a id="del" href="javascript:grilla(<?echo $result['materialesid'];?>)"></a></td>
</tr>
<?
		}
?>
</thead>
</table>
<?
		$cn->close($query);
	}else{
		?>
		<label id="vacio">No hay un Detalle que Mostrar.</label>
		<?
	}
}else if($type=="all"){
$cn = new PostgreSQL();
$query = $cn->consulta("DELETE FROM logistica.tmpcotiza");
$cn->affected_rows($query);
$cn->close($query);
?>
<label id="vacio">No hay un Detalle que Mostrar.</label>
<?
}else{
?>
<label id="vacio">No hay un Detalle que Mostrar.</label>
<?
}
}
?>