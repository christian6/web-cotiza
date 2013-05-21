<?php
/*$path = 'PROP ICR 0198-REF  EDIFICIO TRECCA Rev-00.xlsx';
$filename = substr(strrchr($path, "."), 1);
echo 'materialesid'.'.'.$filename; // "index.html"*/

//	Guardar Tipo de Cambio
/*function Obtener_contenidos($url,$inicio,$final){*/
require_once 'HTTP/Request.php';
$r = new HTTP_Request('http://www.txt.es/clientestxt/index.htm');
$r->setBasicAuth('FDW','PASS');
$r->sendRequest();
$page = $r->getResponseBody();
echo $page;
/*$posicion_inicio = strpos($source, $inicio) + strlen($inicio);
$posicion_final = strpos($source, $final) - $posicion_inicio;
$found_text = substr($source, $posicion_inicio, $posicion_final);
return $inicio . $found_text .$final;
}
$url = 'http://www.sbs.gob.pe/0/home.aspx'; /// pagina web del contenido
$cad = Obtener_contenidos($url,'<p class="WEB_compra">','<div class="WEB_CONTE_cabeceraInferior">');
$cad = substr(trim($cad), 41);
$cad = ereg_replace(" ","",$cad);
$com = substr($cad, 12,7);
$ven = substr($cad, 65,7);
echo TRIM($com);
echo TRIM($ven);*/

//echo file_get_contents('http://www.google.com.pe');
/*$url="http://www.google.com.pe"; // url de la pagina que queremos obtener
$url_content = '';
$file = @fopen($url, 'r') or die('se ha producido un error');
if($file){
  while(!feof($file)) {
    $url_content .= @fgets($file, 4096);
  }
  fclose ($file);
}

echo $url_content;

//$ar =  explode("<span>", Obtener_contenidos($url,'<p class="WEB_compra">','<div class="WEB_CONTE_cabeceraInferior">')); 
// Obtener_contenidos(url,ancla inicio,ancla final);
//echo date("d-m-Y H:m:s");
//echo count($ar);

?>
