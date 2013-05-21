<?php
include("../../datos/postgresHelper.php");


if ($_REQUEST['cmd']=="cbo") {
	if ($_REQUEST['t']=="de") {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT DISTINCT departamentoid,deparnom FROM admin.departamento WHERE paisid LIKE '".$_REQUEST['pa']."' ORDER BY deparnom ASC");
		if ($cn->num_rows($query)>0) {
			echo "de";
			?>
			<select id='cbodepartamento' name="cbodepartamento" OnChange="cbos('pro');" REQUIRED>
			<?php
			while ($result = $cn->ExecuteNomQuery($query)) {
				echo "<option value='".$result['departamentoid']."'>".$result['deparnom']."</option>";
			}
			echo "</select>";
		}
		$cn->close($query);

	}else if ($_REQUEST['t']=="pro"){
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT DISTINCT provinciaid,provnom FROM admin.provincia WHERE paisid LIKE TRIM('".$_REQUEST['pa']."') AND departamentoid LIKE TRIM('".$_REQUEST['de']."') ORDER BY provnom ASC");
		if ($cn->num_rows($query)>0) {
			echo "p";
			?>
			<select id='cboprovincia' name="cboprovincia" OnClick="cbos('d');" REQUIRED>
			<?php
			while ($result = $cn->ExecuteNomQuery($query)) {
				echo "<option value='".$result['provinciaid']."'>".$result['provnom']."</option>";
			}
			echo "</select>";
		}
		$cn->close($query);

	}else if ($_REQUEST['t']=="d") {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT DISTINCT distritoid,distnom FROM admin.distrito WHERE paisid LIKE '".$_REQUEST['pa']."' AND departamentoid LIKE '".$_REQUEST['de']."' AND provinciaid LIKE '".$_REQUEST['pr']."' ORDER BY distnom ASC");
		if ($cn->num_rows($query)>0) {
			echo "d";
			echo "<select id='cbodistrito' name='cbodistrito' REQUIRED>";
			while ($result = $cn->ExecuteNomQuery($query)) {
				echo "<option value='".$result['distritoid']."'>".$result['distnom']."</option>";
			}
			echo "</select>";
		}
		$cn->close($query);
	}
}

if ($_REQUEST['t']=="b") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM admin.empleados WHERE TRIM(empdni) LIKE TRIM('".$_REQUEST['cod']."')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "completado";
}


?>