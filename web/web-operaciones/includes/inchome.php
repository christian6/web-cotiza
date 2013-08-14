<?php
session_start();
include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == "s") {
	$dni = $_SESSION['dni-icr'];
	$to = $_POST['to'];
	$subject = $_POST['subject'];
	$body = $_POST['body'];
	if (trim($subject) != "" and trim($to) != "" ) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO admin.mensaje(empdni,fordni,question,body,esid) VALUES('$dni','$to','$subject','$body','56');");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "hecho";
	}else{
		echo "error";
	}
}elseif($_POST['tra'] == "l"){
	$dni = $_SESSION['dni-icr'];
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT m.nromen,m.fordni,(SELECT n.empnom FROM admin.empleados n WHERE n.empdni LIKE m.empdni) as empnom,
							(SELECT n.empape FROM admin.empleados n WHERE n.empdni LIKE m.empdni) as empape,
							(SELECT r.carnom FROM admin.empleados n INNER JOIN admin.cargo r ON r.cargoid = n.cargoid WHERE n.empdni LIKE m.empdni) as carnom,
							m.fecha::date as fec,to_char(m.fecha,'HH24:MI:SS') as ti,m.question,m.body,m.esid
							FROM admin.mensaje m INNER JOIN admin.empleados e
							ON m.fordni LIKE e.empdni ".
							/*INNER JOIN admin.cargo c
							ON e.cargoid = c.cargoid*/
							" WHERE m.fordni LIKE '$dni' AND m.esid NOT LIKE '57' ORDER by m.fecha DESC LIMIT 10 OFFSET 0;");
	if ($cn->num_rows($query) > 0) {
		echo "<div class='accordion' id='accordion'>";
		$i = 0;
		while ($result = $cn->ExecuteNomQuery($query)) {
		?>
            <div class="accordion-group">
              <div class="accordion-heading">
                <a class="accordion-toggle" style="font-size: 11px;" data-toggle="collapse" data-parent="#accordion" onClick="leido(<?php echo $result['nromen']; ?>);" href="#<?php echo $i; ?>">
                		<?php 
                		if ($result['esid'] == '56'){
                			echo "<i class='icon-tag' title='Mensaje No Leído'></i>&nbsp;&nbsp;";
                			echo "<b>";
                			echo $result['question'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$result['empnom'].' '.$result['empape'];
                			echo "</b>";
                		}else{
                			echo "<i class='icon-tag'></i>&nbsp;&nbsp;";
                			echo $result['question'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$result['empnom'].' '.$result['empape'];
                		}
                		?>
                </a>
              </div>
              <div id="<?php echo $i; ?>" class="accordion-body collapse" style="height: 0px;">
                <div class="accordion-inner">
                	<div class="btn-toolbar">
                		<div class="btn-group">
                			<button class="btn" onClick="location.href='';" title="Lista de Mensajes"><i class="icon-share"></i></button>
                			<button onClick="noleeido(<?php echo $result['nromen']; ?>);" title="Marcar mensaje como no leido" class="btn"><i class="icon-check"></i></button>
                			<button onClick="delete(<?php echo $result['nromen']; ?>);" title="Eliminar" class="btn"><i class="icon-trash"></i></button>
                		</div>
                	</div>
                	<p>
                		<dl class="dl-horizontal">
                			<dt>Nombre</dt>
                			<dd><?php echo $result['empnom'].' '.$result['empape']; ?></dd>
                			<dt>Cargo</dt>
                			<dd><?php echo $result['carnom']; ?></dd>
                			<dt>Fecha</dt>
                			<dd><?php echo $result['fec'].' '.$result['ti']; ?></dd>
                		</dl>
                	</p>
                  	<p>
                  		<?php echo nl2br($result['body']); ?>
                  	</p>
                </div>
              </div>
            </div>
		<?php
			$i++;
		}
		echo "</div>";
	}else{
		echo "No hay mensajes!";
	}
	$cn->close($query);
}elseif ($_POST['tra'] == "r") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT esid FROM admin.mensaje WHERE nromen = ".$_POST['nro'].";");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		echo $result['esid'];
		if ($result['esid'] == 56) {
			$c = new PostgreSQL();
			$q = $c->consulta("UPDATE admin.mensaje SET esid = 55 WHERE nromen = ".$_POST['nro'].";");
			$c->affected_rows($q);
			$c->close($q);
		}
	}
	$cn->close($query);
}

?>