
<?php

session_start();

include ("../../datos/postgresHelper.php");

if ($_POST['tra'] == 'sp') {
	// generando numero de pedido
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM almacen.spnuevopedido()");
	if ($cn->num_rows($query)>0) {
		$result = $cn->ExecuteNomQuery($query);
		$ncod = TRIM($result[0]);
	}
	$cn->close($query);
	// guardando datos de la cabcera de pedido
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO almacen.pedido(nropedido,proyectoid,subproyectoid,sector,empdni,fecent,obser,almacenid,esid) 
							VALUES('$ncod','".$_POST['pro']."',TRIM('".$_POST['sub']."'),'".$_POST['sec']."','".$_POST['dni']."',
							'".$_POST['fec']."'::Date,'".$_POST['obs']."','".$_POST['alm']."','32')");
	$cn->affected_rows($query);
	$cn->close($query);
	// guardando detalle de proyecto
	//echo $_POST['mat'];
	$ids = $_POST['mat'];
	$cn = new PostgreSQL();
	$sql = "SELECT materialesid,cant FROM operaciones.metproyecto WHERE ";
	if ($_POST['sub'] != "") {
		$sql .= "proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND materialesid LIKE ";
	}else{
		$sql .= "proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '' AND materialesid LIKE ";
	}
	for ($i=0; $i < count($ids); $i++) { 
		if( $i == (count($ids) - 1) ){
			$sql .= "'".$ids[$i]."'";
		}else{
			$sql .= "'".$ids[$i]."' OR  materialesid LIKE ";
		}
	}
	//echo $sql;
	//SELECT materialesid,cant FROM operaciones.metproyecto 
	//WHERE proyectoid LIKE '0000000002' AND TRIM(subproyectoid) LIKE '' AND materialesid LIKE
	$query = $cn->consulta($sql);
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c = new PostgreSQL();
			$q = $c->consulta("INSERT INTO almacen.detpedidomat VALUES('$ncod','".$result['materialesid']."',".$result['cant'].",'1','0');");
			$c->affected_rows($q);
			$c->close($q);
			$c = new PostgreSQL();
			$q = $cn->consulta("UPDATE operaciones.metproyecto SET flag = '0' 
				WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' 
				AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialesid LIKE '".$result['materialesid']."'");
			$c->affected_rows($q);
			$c->close($q);
		}
	}
	$cn->close($query);
	echo $ncod;
}
if ($_POST['tra'] == 'tmpnip') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO operaciones.tmpniples(dni,proyectoid,subproyectoid,sector,adicionalid,materialid,metrado,tipo) VALUES('".$_SESSION['dni-icr']."',
							'".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$_POST['adi']."','".$_POST['matid']."',".$_POST['met'].",'".$_POST['tip']."');");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'listnip') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT id,dni,materialid,metrado,tipo,(select sum(metrado)as tot from operaciones.tmpniples WHERE materialid LIKE '".$_POST['mat']."' ) as tot FROM operaciones.tmpniples 
		WHERE dni LIKE '".$_SESSION['dni-icr']."' AND materialid LIKE '".$_POST['mat']."'
		GROUP BY id,dni,materialid,metrado,tipo;");
	if ($cn->num_rows($query) > 0 ) {
		$tot = 0;
		echo "<table class='table t-info'>";
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<tr>";
			echo "<td>".$result['id']."</td>";
			echo "<td>".$result['materialid']."</td><td>".$_POST['med']."''</td><td>&times;</td><td>".$result['metrado']."</td><td>".$result['tipo']."</td>";
			echo "<td><button class='btn btn-mini' OnClick=delniple(".$result['id'].",'".$result['materialid']."','".$_POST['med']."');><i class='icon-trash'></i></button></td>";
			echo "</tr>";
			$tot = $result['tot'];
		}
		echo "</table>";
	}
	$cn->close($query);
	echo "|success|".$tot;
}
if ($_POST['tra'] == 'delniple') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.tmpniples WHERE dni LIKE '".$_SESSION['dni-icr']."' AND id = ".$_POST['id']."");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'upfile') {
	$folder = $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/pedidos/';
	$return = 'success';
	if (!file_exists($folder)) {
		mkdir($folder);
		chmod($folder, 0777);
	}
	$tmp_file = $_FILES['fpedido']['tmp_name'];
	$file = $folder.$_POST['nrop'].'.pdf';
	if (!move_uploaded_file($tmp_file, $file)) {
		$return = 'Error load file';
	}
	if ($return == 'success') {
		chmod($file, 0777);
	}
	echo $return;
}
if ($_POST['tra'] == 'saveniple') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT * FROM operaciones.tmpniples WHERE dni LIKE '".$_SESSION['dni-icr']."' AND proyectoid LIKE '".$_POST['pro']."' AND
							TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialid LIKE '".$_POST['mat']."'");
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$c = new PostgreSQL();
			$q = $c->consulta("INSERT INTO operaciones.niples VALUES('".$result['proyectoid']."','".$result['subproyectoid']."','".$result['sector']."',
							'','".$_POST['nro']."','".$result['materialid']."','".$result['metrado']."','".$result['tipo']."');");
			$c->affected_rows($q);
			$c->close($q);
		}
	}
	$cn->close($query);
	$cn = new PostgreSQL();
	$query = $cn->consulta("DELETE FROM operaciones.tmpniples WHERE dni LIKE '".$_SESSION['dni-icr']."' AND proyectoid LIKE '".$_POST['pro']."' AND
							TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."' AND materialid LIKE '".$_POST['mat']."'");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}
if ($_POST['tra'] == 'tmpmod') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT COUNT(*) FROM operaciones.tmpmodificaciones WHERE proyectoid LIKE '".$_POST['pro']."' AND 
							TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."';");
	$count = 0;
	if ($cn->num_rows($query) > 0) {
		while ($result = $cn->ExecuteNomQuery($query)) {
			$count = $result[0];
		}
	}
	$cn->close($query);
	if ($count <= 0) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO operaciones.tmpmodificaciones SELECT * FROM operaciones.metproyecto
		WHERE proyectoid LIKE '".$_POST['pro']."' AND TRIM(subproyectoid) LIKE '".$_POST['sub']."' AND TRIM(sector) LIKE '".$_POST['sec']."';");
		$cn->affected_rows($query);
		$cn->close($query);
	}
	$cn = new PostgreSQL();
	$sql = "SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant,d.flag
			FROM operaciones.tmpmodificaciones d INNER JOIN admin.materiales m
			ON d.materialesid LIKE m.materialesid
			INNER JOIN ventas.proyectos p
			ON d.proyectoid LIKE p.proyectoid 
			WHERE d.proyectoid LIKE '".$_POST['pro']."' AND TRIM(d.subproyectoid) LIKE TRIM('".$_POST['sub']."') AND TRIM(d.sector) LIKE TRIM('".$_POST['sec']."')
			GROUP BY d.materialesid,m.matnom,m.matmed,m.matund,d.flag";
	$query = $cn->consulta($sql);
	if ($cn->num_rows($query) > 0) {
		$i = 1;
		$tot = 0;
		while ($result = $cn->ExecuteNomQuery($query)) {
			if($result['flag'] == '0'){ echo "<tr class='c-red-light'>";}else{ echo "<tr>"; }
			echo "<td id='tc'>".$i++."</td>";
			echo "<td>".$result['materialesid']."</td>";
			echo "<td>".$result['matnom']."</td>";
			echo "<td>".$result['matmed']."</td>";
			echo "<td id='tc'>".$result['matund']."</td>";
			echo "<td id='tc'>".$result['cant']."</td>";
			echo "<td><input style='height: 1.1em; text-align: right;' type='number' max='9999' min='0' onBlur=modifyCant('".$result['materialesid']."',this.value); class='input-small' value='".$result['cant']."' REQUIRED ";
			if($result['flag'] == '0'){ echo "DISABLED";}
			echo "/></td>";
			echo "<td><button class='btn btn-mini btn-danger' OnClick=delmodifymat('".$result['materialesid']."');><i class='icon-remove'></i></td>";
			echo "</tr>";
			$c = new PostgreSQL();
			$q = $c->consulta("SELECT * FROM operaciones.sp_search_stock_mat('".$result['materialesid']."');");
			if ($c->num_rows($q) > 0) {
				$r = $c->ExecuteNomQuery($q);
				$tot += ($result['cant'] * $r[1]);
			}
			$c->close($q);
		}
		
	}
	$cn->close($query);
	echo "|success|".$tot;
}
if ($_POST['tra'] == 'msgsec') {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO ventas.alertasec(proyectoid,subproyectoid,sector,msg,tm) VALUES('".$_POST['pro']."','".$_POST['sub']."','".$_POST['sec']."','".$_POST['obs']."','".$_POST['tfr']."')");
	$cn->affected_rows($query);
	$cn->close($query);
	echo "success";
}

if ($_POST['tra'] == 'upmo') {
	
	if ($_POST['sub'] == '') {
		$folder = $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/modify/'.$_POST['sec'];
		$path = 'modify/'.$_POST['sec'];
	}else{
		$folder = $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/modify/'.$_POST['sub'].'/'.$_POST['sec'];
		$path = 'modify/'.$_POST['sub'].'/'.$_POST['sec'];
	}
	$return = 'success';
	$path = explode('/', $path);
	$base = $_SERVER['DOCUMENT_ROOT'].'/web/project/'.$_POST['pro'].'/';
	for ($i=0; $i < count($path); $i++) { 
		$base = $base.$path[$i].'/';
		if (!file_exists($base)) {
			mkdir($base);
			chmod($base, 0777);
		}
	}
	/*if (!file_exists($folder)) {
		mkdir($folder);
		chmod($folder, 0777);
		echo "se crea ";
	}else{
		echo "si existe";
	}*/
	$tmp_file = $_FILES['pmo']['tmp_name'];
	$fec = date("Ymd-Hi");
	$file = $folder.'/'.$_POST['sec'].'-'.$fec.'.pdf';
	if (!move_uploaded_file($tmp_file, $file)) {
		$return = 'Error load file';
	}
	if ($return == 'success') {
		chmod($file, 0777);
	}
	echo $return;
}

?>