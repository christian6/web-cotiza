<?php
include("../../datos/postgresHelper.php");

if ($_REQUEST['t']=="b") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.proyectos SET esid = '25' WHERE TRIM(proyectoid) LIKE TRIM('".$_REQUEST['cod']."')");
	$cn->affected_rows($query);
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.subproyectos SET esid = '28' WHERE TRIM(proyectoid) LIKE TRIM('".$_REQUEST['cod']."')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "completado";
}
if ($_REQUEST['t']=="s") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT subproyectoid,subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE TRIM('".$_REQUEST['proid']."')");
	if ($cn->num_rows($query)>0) {
		echo "<select id='cbosub' name='cbosub'>";
		while ($result = $cn->ExecuteNomQuery($query)) {
		?>
		<option value="<?echo $result['subproyectoid'];?>"><?echo $result['subproyecto'];?></option>
		<?php
		}
		echo "</select>";
	}
	$cn->close($query);
}
?>
