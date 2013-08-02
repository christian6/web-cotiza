<?php
include ("../../datos/postgresHelper.php");

if ($_REQUEST['tra']=='iii') {
	$ex = -1;
	$cn = new PostgreSQL();
	$query = $cn->consulta("SELECT count(*) FROM almacen.inventario WHERE materialesid like '".$_REQUEST['matid']."' and anio LIKE '".date('Y')."'");
	if ($cn->num_rows($query) > 0) {
		$result = $cn->ExecuteNomQuery($query);
		$ex = $result[0];
	}
	$cn->close($query);

	if ($ex == 0) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("
			INSERT INTO almacen.inventario VALUES('".$_REQUEST['matid']."',
			'".$_REQUEST['cboal']."',0,10,0,0,0,'".date('Y')."',now()::date,'','10704928501','23'
			);");
		$cn->affected_rows($query);
		$cn->close($query);

		$ck = new PostgreSQL();
		$qk = $ck->consulta("INSERT INTO almacen.entradasalida(tdoc,nrodoc,almacenid,materialesid,stkact,cantent,cantsal,saldo,precio,flag)
    					VALUES ('II','0000000000000000','".$_REQUEST['cboal']."','".$result['matid']."',0, 0,0, 0, 0,'1');");
		$ck->affected_rows($qk);
		$ck->close($qk);
		echo "hecho";
	}else if($ex > 0){
		echo "none";
	}

}

?>