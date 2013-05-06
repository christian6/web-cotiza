<?php
include ("../../datos/postgresHelper.php");

$nom = $_REQUEST['nom'];
$med = $_REQUEST['med'];

if (isset($nom) && isset($med)) {

$cn = new PostgreSQL();
$query = $cn->consulta("SELECT materialesid,matnom,matmed,matund FROM admin.materiales WHERE matnom LIKE '$nom' and matmed LIKE '$med'");
if ($cn->num_rows($query)>0) {
	while ($result = $cn->ExecuteNomQuery($query)) {
?>
<div id="rdata">

<label id="ldata">Codigo: <input type="text" id="txtcod" value="<?echo $result['materialesid'];?>" DISABLED/></label>
<label id="ldata">Descripcion: <input type="text" id="txtnom" value='<?echo $result['matnom'];?>' DISABLED/></label>
<br />
<label id="ldata">Unidad: <input type="text" id="txtund" value='<?echo $result['matund'];?>' DISABLED/></label>
<label id="ldata">Medida: <input type="text" id="txtmed" value='<?echo $result['matmed'];?>' DISABLED/></label>
<br />
<hr />

<label id="ldata">Cantidad: </label>
<input type="number" id="txtcant" name="txtcant" placeholder="Ingrese Cantidad" min='0' max='500' REQUIERE/>
<br />
<input type="Button" id="btnadd" name="btnadd" value="Agregar" onclick="grilla('add');" />
</div>
<?
	}
}
}else{
?>
<label id="vacio">No hay Data</label>
<?
}
?>