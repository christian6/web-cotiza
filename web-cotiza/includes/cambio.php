<?php

include("../datos/postgresHelper.php");


function Obtener_contenidos($url,$inicio,$final){
	$source = @file_get_contents($url)or die('se ha producido un error');
	$posicion_inicio = strpos($source, $inicio) + strlen($inicio);
	$posicion_final = strpos($source, $final) - $posicion_inicio;
	$found_text = substr($source, $posicion_inicio, $posicion_final);
	return $inicio . $found_text .$final;
}


$cn = new PostgreSQL();
$query = $cn->consulta("SELECT * FROM admin.spconsultatc()");
if ($cn->num_rows($query)>0) {
	while ($result =  $cn->ExecuteNomQuery($query)) {
		if ($result[0] == "existe") {
			$cn->close($query);
			exit();
		}else if($result[0] == "noexiste"){
			//	Guardar Tipo de Cambio
			
			$url = 'http://190.102.151.31/0/home.aspx'; /// pagina web del contenido
			$ini = '<p class="WEB_compra">';
			$fin = '<div class="WEB_CONTE_cabeceraInferior">';
			$cad = Obtener_contenidos($url,$ini,$fin);
			$cad = substr(trim($cad), 41);
			$cad = ereg_replace(" ","",$cad);
			$com = substr($cad, 12,7);
			$ven = substr($cad, 65,7);
			$cn2 = new PostgreSQL();
			$query2 = $cn2->consulta("SELECT * FROM admin.spgrabartipocambio('00002',$com,$ven)");
			$result2 = $cn->ExecuteNomQuery($query2);
			echo $result2[0];
			$cn2->close($query2);
		}
	}
}else{
	$cn->close($query);
	exit();
}
?>