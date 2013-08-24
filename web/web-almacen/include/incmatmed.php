<?php
session_start();

include ("../../datos/postgresHelper.php");

$nom = $_REQUEST['nom'];
$tipo = $_REQUEST['tipo'];

if($tipo == "nom"){
?>
	<label>Seleccione la Medida: </label>
	<select id="matmed" class="span3" name="matmed" onclick="dat();">
<?php
	$cn=new PostgreSQL();
	$query=$cn->consulta("SELECT m.matmed FROM admin.materiales m INNER JOIN almacen.inventario i ON m.materialesid=i.materialesid 
		WHERE TRIM(lower(m.matnom)) LIKE TRIM(lower('".$nom."')) AND i.anio LIKE '".date("Y")."'");
	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<option value='".$result['matmed']."'>".$result['matmed']."</option>";
		}
	}
	$cn->close($query);
?>
	</select>
<?php
}else{
}


$nom = $_REQUEST['nom'];
$med = $_REQUEST['med'];

if ($tipo == "med") {

$cn = new PostgreSQL();
$query = $cn->consulta("SELECT i.materialesid,m.matnom,m.matmed,m.matund FROM admin.materiales m INNER JOIN almacen.inventario i ON m.materialesid=i.materialesid WHERE m.matnom LIKE '$nom' and m.matmed LIKE '$med' AND i.anio LIKE '".date("Y")."'");
if ($cn->num_rows($query)>0) {
	while ($result = $cn->ExecuteNomQuery($query)) {
?>
<div id="rdata">
	<table>
		<tr>
			<td>
				<label id="ldata">Codigo:</label> <input type="text" class=" input-medium" id="txtcod" value="<?echo $result['materialesid'];?>" DISABLED/>
			</td>
			<td>
				<label id="ldata">Descripcion:</label><input type="text" class="span5" id="txtnom" value='<?echo $result['matnom'];?>' DISABLED/>
			</td>
		</tr>
		<tr>
			<td>
			<label id="ldata">Unidad: </label><input type="text" id="txtund" value='<?echo $result['matund'];?>' DISABLED/>
			</td>
			<td>
			<label id="ldata">Medida:</label><input type="text" id="txtmed" value='<?echo $result['matmed'];?>' DISABLED/>
			</td>
		</tr>
		<tr>
			<td>
				<label id="ldata">Cantidad: </label>
			</td>
			<td>
				<input type="number" id="txtcant" class=" input-medium" name="txtcant" placeholder="Ingrese Cantidad" min='0' max='1000' step="0.01" REQUIERE/>
			</td>
		</tr>
		<tr>
			<td>
		<button type="Button" id="btnadd" class="btn btn-primary" name="btnadd" onclick="grilla('add');"><i class="icon-shopping-cart"></i> Agregar</button>
			</td>
		</tr>
	</table>
</div>
<?
	}
}
$cn->close($query);
}else{
?>
<?
}

if ($tipo == "grilla") {

	$dni = TRIM($_SESSION['dni-icr']);
	$type = $_REQUEST['tip'];
	$cod = $_REQUEST['cod'];
	$cant = $_REQUEST['cant'];

	if ($type == "add") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.tmppedido VALUES('$dni','$cod',$cant)");
	$cn->affected_rows($query);
	$cn->close();
	}else if($type == "del"){
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM almacen.tmppedido WHERE materialesid like '$cod' AND empdni LIKE '$dni'");
	$cn->affected_rows($query);
	$cn->close($query);
	}else if($type=="all"){
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM almacen.tmppedido WHERE empdni LIKE '$dni'");
	$cn->affected_rows($query);
	$cn->close($query);
	?>
	<label id="vacio">No hay un Detalle que Mostrar.</label>
	<?
}

	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT t.materialesid,m.matnom,m.matmed,m.matund,t.cantidad FROM almacen.tmppedido t INNER JOIN admin.materiales m ON t.materialesid=m.materialesid INNER JOIN almacen.inventario i ON m.materialesid=i.materialesid WHERE t.empdni LIKE '$dni' AND i.anio LIKE '".date('Y')."' ORDER BY m.matnom ASC");
	if ($cn->num_rows($query)>0) {
?>
<table id="tbldetalle">
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
<td style="text-align:center;"><?echo $result['matund'];?></td>
<td style="text-align:center;"><?echo $result['cantidad'];?></td>
<td style="text-align:center;"><a id="del" href="javascript:grilla(<?echo $result['materialesid'];?>)"><img src="../resource/delete.png"></a></td>
</tr>
<?
		}
?>
</thead>
</table>
<?
	}
	$cn->close($query);
}


if ($tipo == "sub") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT subproyectoid,subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$_REQUEST['pro']."' AND esid LIKE '26'");
	if ($cn->num_rows($query) > 0) {
		echo "<td>Sub proyecto</td>";
		echo "<td>";
		echo "<select id='cbosub' onClick='subsec();'>";
		while ($result =  $cn->ExecuteNomQuery($query)) {
			?>
				<option value="<?echo $result['subproyectoid'];?>"><?echo $result['subproyecto'];?></option>
			<?php
		}
		echo "</td>";
		echo "</select>";
	}else{
		$cn->close($query);
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT sector,descripcion FROM ventas.sectores WHERE proyectoid LIKE '".$_REQUEST['pro']."' AND esid LIKE '29'");
		if ($cn->num_rows($query) > 0) {
			echo "<td>Sector:</td>";
			echo "<td>";
			echo "<select id='cbosec'>";
			while ($result =  $cn->ExecuteNomQuery($query)) {
				?>
				<option value="<?echo $result['sector']?>"><?echo $result['descripcion'];?></option>
				<?php
			}
			echo "</select>";
			echo "</td>";
		}
		$cn->close($query);
	}
}

if ($tipo == "subsec") {
	$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT sector,descripcion FROM ventas.sectores WHERE proyectoid LIKE '".$_REQUEST['pro']."' AND subproyectoid LIKE '".$_REQUEST['sub']."' AND esid LIKE '29'");
		if ($cn->num_rows($query) > 0) {
			echo "<td>Sector:</td>";
			echo "<td>";
			echo "<select id='cbosec'>";
			while ($result =  $cn->ExecuteNomQuery($query)) {
				?>
				<option value="<?echo $result['sector']?>"><?echo $result['descripcion'];?></option>
				<?php
			}
			echo "</select>";
			echo "</td>";
		}
	$cn->close($query);
}

if ($tipo == "save") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM almacen.spnuevopedido()");
	if ($cn->num_rows($query)>0) {
		$result = $cn->ExecuteNomQuery($query);
		$ncod = TRIM($result[0]);
	}
	$cn->close($query);

	$cn = new PostgreSQL();
	$pro = $_REQUEST['pro'];
	$sub = $_REQUEST['sub'];
	$sec = $_REQUEST['sec'];
	$fec = $_REQUEST['fec'];
	$obs = $_REQUEST['obs'];
	$al = $_REQUEST['al'];
	$dni = $_SESSION['dni-icr'];

	$query = $cn->consulta("INSERT INTO almacen.pedido(nropedido,proyectoid,subproyectoid,sector,empdni,fecent,obser,almacenid,esid) VALUES('$ncod','$pro',TRIM('$sub'),'$sec',TRIM('$dni'),to_date('$fec','yyyy-mm-dd'),'$obs','$al','32')");
	$cn->affected_rows($query);
	$cn->close($query);
	
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT materialesid, cantidad FROM almacen.tmppedido WHERE empdni = '".$dni."'");
	if ($cn->num_rows($query)>0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$cn2 = new PostgreSQL();
			$query2 = $cn2->consulta("INSERT INTO almacen.detpedidomat VALUES('$ncod','".$result['materialesid']."',".$result['cantidad'].",'1')");
			$cn2->affected_rows($query2);
			$cn2->close($query2);
		}
	}
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM almacen.tmppedido WHERE empdni LIKE '$dni'");
	$cn->affected_rows($query);
	$cn->close($query);

	echo $ncod;
}


?>