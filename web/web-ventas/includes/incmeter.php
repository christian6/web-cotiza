<?php

include ("../../datos/postgresHelper.php");

if ($_REQUEST['tra'] == 'med') {
	$nom = $_REQUEST['nom'];

	$cn=new PostgreSQL();
	$query=$cn->consulta("SELECT DISTINCT m.matmed FROM admin.materiales m
						WHERE TRIM(lower(m.matnom)) LIKE TRIM(lower('".$nom."')) ORDER BY m.matmed ASC;");
	if ($cn->num_rows($query)>0) {
		echo "<select class='span6' name='cbomed' id='cbomed' onClick='showdet();' onChange='showdet();'>";
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<option value='".$result['matmed']."'>".$result['matmed']."</option>";
		}
		echo "</select>";
	}
	$cn->close($query);

}else if($_REQUEST['tra'] == 'data'){
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT m.materialesid,m.matnom,m.matmed,m.matund FROM
							admin.materiales m 
							WHERE  TRIM(LOWER(m.matnom)) LIKE TRIM(LOWER('".$_REQUEST['nom']."')) AND 
							TRIM(LOWER(m.matmed)) LIKE TRIM(LOWER('".$_REQUEST['med']."'))	LIMIT 1 OFFSET 0");
	$cod = "";
	$nom = "";
	$med = "";
	$und = "";
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$cod = $result['materialesid'];
			$nom = $result['matnom'];
			$med = $result['matmed'];
			$und = $result['matund'];
		}
	}
	$cn->close($query);
?>
	<!--<dl class="dl-horizontal">
		<dt>Codigo</dt>
		<dd id="cod"><?php# echo $cod; ?></dd>
		<dt>Nombre</dt>
		<dd><?php# echo $nom; ?></dd>
		<dt>Medida</dt>
		<dd><?php #echo $med; ?></dd>
		<dt>Unidad</dt>
		<dd><?php #echo $und; ?></dd>
	</dl>-->
	<table style="background-color: rgba(255,255,255,0);" class="table table-condensed">
		<tbody style="background-color: rgba(255,255,255,0);">
			<tr>
				<th>Codigo</th>
				<td id="cod"><?php echo $cod; ?></td>
			</tr>
			<tr>
				<th>Decripción</th>
				<td><?php echo $nom; ?></td>
			</tr>
			<tr>
				<th>Medida</th>
				<td><?php echo $med; ?></td>
			</tr>
			<tr>
				<th>Unidad</th>
				<td><?php echo $und; ?></td>
			</tr>
		</tbody>
	</table>
<?php
}else if($_REQUEST['tra'] == 'save'){

	$cod = $_REQUEST['cod'];
	$pro = $_REQUEST['proid'];
	$pla = $_REQUEST['pla'];
	$cant = $_REQUEST['cant'];

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.matmetrado VALUES('$pro','','$pla','$cod',$cant,'1')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}
if ($_POST['tra'] == 'savepro') {
	$pro = '';
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM ventas.spnuevoproyecto()");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		$pro = $result[0];
	}
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.proyectos(proyectoid, descripcion, fecent, ruccliente, direccion, 
            paisid, departamentoid, provinciaid, distritoid, obser, esid,feccom)
			VALUES('".$pro."','".$_POST['nom']."','".$_POST['fec']."'::date,'".$_POST['cli']."','".$_POST['dir']."','".$_POST['pais']."',
			'".$_POST['dep']."','".$_POST['pro']."','".$_POST['dis']."','".$_POST['obs']."','17','".$_POST['feci']."'::date)");
	$cn->affected_rows($query);
	$cn->close($query);
	echo $pro;
}

if ($_POST['tra'] == 'savesec') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.sectores VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."',
							'".$_POST['des']."','".$_POST['obs']."','29');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "hecho";
}else if($_POST['tra'] == 'savesub'){
	$sub = '';
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM ventas.spnuevosubproyecto();");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		$sub = $result[0];
	}
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.subproyectos(subproyectoid,proyectoid,subproyecto,fecent,obser,esid)
							VALUES('".$sub."','".$_POST['pro']."','".$_POST['des']."','".$_POST['fec']."'::date,'".$_POST['obs']."','26');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo $sub;
}else if ($_POST['tra'] == 'savead') {
	$adi = '';
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM ventas.spnuevoadicionales();");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		$adi = $result[0];
	}
	$cn->close($query);

	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.adicionales VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$adi."',
							'".$_POST['des']."','".$_POST['obs']."','56');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo $adi;
}
if ($_POST['tra'] == 'valsec') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM ventas.adicionales WHERE proyectoid LIKE '".$_POST['pro']."' AND 
							TRIM(subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(nroplano) LIKE TRIM('".$_POST['sec']."')");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			if ($result[0] >= 1) {
				echo "success";
			}else{
				echo "Fail";
			}
		}
	}else{
		echo "Fail";
	}
	$cn->close($query);
}
if($_POST['tra'] == 'savedata'){

	$cod = $_POST['id'];
	$pro = $_POST['pro'];
	$pla = $_POST['sec'];
	$sub = $_POST['sub'];
	$cant = $_POST['cant'];
	$c = new PostgreSQL();
	$q = $c->consulta("SELECT COUNT(*) FROM ventas.matmetrado WHERE proyectoid LIKE '".$_POST['pro']."' AND 
						TRIM(subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(sector) LIKE TRIM('".$_POST['sec']."') AND 
						materialesid LIKE '".$_POST['id']."'");
	if ($c->num_rows($q) > 0) {
		$r = $c->ExecuteNomQuery($q);
		if ($r[0] >= 1) {
			echo "exists";
		}else{
			$cn = new PostgreSQL();
			$query = $cn->consulta("INSERT INTO ventas.matmetrado VALUES('$pro','$sub','$pla','$cod',$cant,'1');");
			$cn->affected_rows($query);
			$cn->close($query);
			echo "success";
		}
	}
	$c->close($q);	
}
if ($_POST['tra'] == 'listtbl') {
	$cn = new PostgreSQL();
	$qsql = "";
	$query = $cn->consulta("SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant
							FROM ventas.matmetrado d INNER JOIN admin.materiales m
							ON d.materialesid LIKE m.materialesid
							WHERE d.proyectoid LIKE '".$_POST['pro']."' AND TRIM(d.subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."'
							GROUP BY d.materialesid,m.matnom,m.matmed,m.matund	
							");
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr>";
			echo "<td id='tc'>".$i++."</td>";
			echo "<td>".$result['materialesid']."</td>";
			echo "<td>".$result['matnom']."</td>";
			echo "<td>".$result['matmed']."</td>";
			echo "<td id='tc'>".$result['matund']."</td>";
			echo "<td id='tc'>".$result['cant']."</td>";
			?>
			<td id="tc"><a href="javascript:showedit('<?php echo $result['materialesid']; ?>','<?php echo $result['matnom']; ?>','<?php echo str_replace('"', '', $result['matmed']); ?>',<?php echo $result['cant']; ?>);"><i class="icon-edit"></i></a></td>
			<td id="tc"><a href="javascript:delmat('<?php echo $result['materialesid']; ?>','<?php echo $result['matnom']; ?>','<?php echo str_replace('"', '', $result['matmed']); ?>');"><i class="icon-trash"></i></a></td>
			<?php
			echo "</tr>";
		}
	}
	$cn->close();
}
if ($_POST['tra'] == "aproved") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM ventas.proyectopersonal WHERE proyectoid LIKE '".$_POST['pro']."'");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		if ($result[0] == 0) {
			echo "nothing";
		}else{
			echo "success";
		}
	}else{
		echo "nothing";
	}
	$cn->close($query);
}
if ($_POST['tra'] == 'prostatus') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.proyectos SET esid = '59' WHERE proyectoid LIKE '".$_POST['pro']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'delmat') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM ventas.matmetrado WHERE proyectoid LIKE '".$_POST['pro']."' AND 
							TRIM(subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(sector) LIKE TRIM('".$_POST['sec']."') AND materialesid LIKE '".$_POST['id']."';");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'editmat') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("UPDATE ventas.matmetrado SET cant = ".$_POST['cant']." WHERE proyectoid LIKE '".$_POST['pro']."' AND 
							TRIM(subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(sector) LIKE TRIM('".$_POST['sec']."') AND materialesid LIKE '".$_POST['id']."';");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'newmat') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM admin.materiales WHERE materialesid LIKE '".$result['matid']."'");
	if ($cn->num_rows($query) > 0) {
		$res = $cn->ExecuteNomQuery($query);
	}
	$cn->close($query);
	if ($res[0] >= 1) {
		echo "exists";
	}else{
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO admin.materiales VALUES('".$_POST['matid']."','".$_POST['nom']."','".$_POST['med']."',
							'".$_POST['und']."',0,'".$_POST['mar']."','".$_POST['mod']."','".$_POST['aca']."',0)");
		$cn->affected_rows($query);
		$cn->close($query);
		$cn =  new PostgreSQL();
		$query = $cn->consulta("INSERT INTO almacen.inventario VALUES('".$_POST['matid']."','0001',0,0,0,0,0,0,
							extract(year FROM now())::CHAR(4),now()::date,'0000000000','10704928501','23')");
		$cn->affected_rows($query);
		$cn->close($query);
		echo "success";
	}	
}

/* ###################################################################################################################### */

if ($_POST['tra'] == 'lpro') {
	$lpro = array();
	try {
		$sql = "SELECT p.proyectoid,p.descripcion,c.nombre FROM ventas.proyectos p INNER JOIN admin.clientes c ON p.ruccliente LIKE c.ruccliente WHERE ";
		if ($_POST['tipo'] == 'nro') {
			$sql .= " proyectoid LIKE '".$_POST['nro']."' ORDER BY descripcion ASC ";
		}else{
			$sql.= " lower(descripcion) LIKE '%".$_POST['nom']."%' ORDER BY descripcion ASC ";
		}
		$cn = new PostgreSQL();
		$query = $cn->consulta($sql);
		if ($cn->num_rows($query) > 0 ) {
			$res = array();
			while ($result = $cn->ExecuteNomQuery($query)){
				$res[] = array('proyectoid' =>$result['proyectoid'], 'descripcion' =>$result['descripcion'], 'razonsocial' => $result['nombre']);
			}
			$lpro['status'] = 'success';
			$lpro['list'] = $res;
			
		}
	} catch (Exception $e) {
		$lpro['status'] = 'fail';
	}
	echo json_encode($lpro);
}
if ($_POST['tra'] == 'lsec') {
	$lsec = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT DISTINCT nroplano,sector FROM ventas.sectores WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE TRIM('".$_POST['sub']."') ");
		if ($cn->num_rows($query) > 0) {
			$res = array();
			while ($result = $cn->ExecuteNomQuery($query)){
				$res[] = array('nroplano' =>$result['nroplano'], 'sector' =>$result['sector']);
			}
			$lsec['status'] = 'success';
			$lsec['list'] = $res;
		}
		$cn->close($query);
	} catch (Exception $e) {
		$lsec['status'] = 'fail';
	}
	echo json_encode($lsec);
}
if ($_POST['tra'] == 'lsub') {
	$lsec = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT DISTINCT subproyectoid,subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$_POST['pro']."'");
		if ($cn->num_rows($query) > 0) {
			$res = array();
			while ($result = $cn->ExecuteNomQuery($query)){
				$res[] = array('subproyectoid' =>$result['subproyectoid'], 'subproyecto' =>$result['subproyecto']);
			}
			$lsec['status'] = 'success';
			$lsec['list'] = $res;
		}
		$cn->close($query);
	} catch (Exception $e) {
		$lsec['status'] = 'fail';
	}
	echo json_encode($lsec);
}
if ($_POST['tra'] == 'lmat') {
	$lmat = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant
														FROM ventas.matmetrado d INNER JOIN admin.materiales m
														ON d.materialesid LIKE m.materialesid
														WHERE d.proyectoid LIKE '".$_POST['pro']."' AND TRIM(d.subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(d.sector) LIKE '".$_POST['sec']."'
														GROUP BY d.materialesid,m.matnom,m.matmed,m.matund");
		if ($cn->num_rows($query) > 0) {
			$res = array();
			while ($result = $cn->ExecuteNomQuery($query)) {
				$res[] = array('materialesid' =>$result['materialesid'], 'matnom' =>$result['matnom'], 'matmed' =>$result['matmed'], 'matund' =>$result['matund'], 'cant' =>$result['cant']);
			}
		}
		$lmat['status'] = 'success';
		$lmat['list'] = $res;
		$cn->close($query);
	} catch (Exception $e) {
		$lsec['status'] = 'fail';
	}
	echo json_encode($lmat);
}
if ($_POST['tra'] == 'savedcopy') {
	$tma = array();
	try {
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT DISTINCT d.materialesid,SUM(d.cant) as cant FROM ventas.matmetrado d	WHERE d.proyectoid LIKE '".$_POST['pro']."' AND TRIM(d.subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(d.sector) LIKE '".$_POST['sec']."' AND d.materialesid in (".$_POST['mat'].") GROUP BY d.materialesid");
		if ($cn->num_rows($query) > 0) {
			while ($result = $cn->ExecuteNomQuery($query)) {
				$c = new PostgreSQL();
				$q = $c->consulta("INSERT INTO ventas.matmetrado VALUES('".$_POST['pron']."','".$_POST['subn']."','".$_POST['secn']."','".$result['materialesid']."',".$result['cant'].",'1');");
				$c->affected_rows($q);
				$c->close($q);
			}
		}
		$cn->close($query);
		$tma['status'] = 'success';
	} catch (Exception $e) {
		$tma['status'] = 'fail';
	}
	echo json_encode($tma);
}
?>