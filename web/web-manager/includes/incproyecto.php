<?php
session_start();

include ("../../includes/session-trust.php");

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'pro') {
	$cn = new PostgreSQL();
	$qsql = "SELECT p.proyectoid,p.descripcion,p.fecent,e.esnom FROM ventas.proyectos p
			INNER JOIN admin.estadoes e
			ON p.esid = e.esid
			INNER JOIN ventas.proyectopersonal r
			ON p.proyectoid LIKE r.proyectoid
			WHERE  ";

	if (sestrust('sk') == 1) {
		if ($_POST['anio'] == 'all') {
			$qsql .= " lower(p.descripcion) LIKE lower('%".$_POST['nom']."%') ORDER BY p.fecha DESC";
		}else{
			$qsql .= " lower(p.descripcion) LIKE lower('%".$_POST['nom']."%') AND extract(year from p.fecha)::char(4) LIKE '".$_POST['anio']."' ORDER BY p.fecha DESC";
		}
		
	}else if (sestrust('sk') == 0) {
		if ($_POST['anio'] == 'all') {
			$qsql .= " lower(p.descripcion) LIKE lower('%".$_POST['nom']."%') AND r.empdni LIKE '".$_SESSION['dni-icr']."' ORDER BY p.fecha DESC";
		}else{
			$qsql .= " lower(p.descripcion) LIKE lower('%".$_POST['nom']."%') AND extract(year from p.fecha)::char(4) LIKE '".$_POST['anio']."' AND r.empdni LIKE '".$_SESSION['dni-icr']."' ORDER BY p.fecha DESC";
		}
		
	}

	$query = $cn->consulta($qsql);
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
	?>
	<article>
		<a id="txts" href="sectores.php?proid=<?php echo $result['proyectoid']; ?>">
			<i class="icon-map-marker icon-white"></i>
			<label for="label"><?php echo $result['proyectoid']; ?></label>
			<p><?php echo $result['descripcion']; ?></p>	
		</a>
	</article>
	<?php
		}
	}
	$cn->close($query);
}

?>