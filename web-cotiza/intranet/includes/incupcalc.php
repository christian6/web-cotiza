<?
function array_envia($array) { 

     $tmp = serialize($array); 
     $tmp = urlencode($tmp); 

     return $tmp; 
} 

include("../../datos/postgresHelper.php");
$cod=$_REQUEST['lstcod'];
if (isset($cod)) {
	$array=explode(",", $cod);
	echo "<form name='frmupload' method='POST' enctype='multipart/form-data' action=''>";
	echo "<table border='0'>";
	//$array = array("00001","00002");
	$arrayt=array_envia($array); 
	for ($i=0; $i < count($array); $i++) { 
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT descripcion FROM ventas.proyectos WHERE proyectoid LIKE '".$array[$i]."'");
		if($cn->num_rows($query)>0){
			while($result = $cn->ExecuteNomQuery($query)){
		?>
		<tr>
		<td><label for="nompro" id="<?echo $array[$i];?>"><?echo $result['descripcion'];?></label></td>
		<td><input type="file" name="calc[]" id="<?echo $array[$i];?>" accept=".xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  REQUIRED /></td>
		</tr>
		<?
			}
		}
		$cn->close($query);

	}
	echo "<input name='array' type='hidden' value='$arrayt'>";
	echo "</table>";
	echo "<input type='Submit' value='Leer Calc' />";
	echo "</form>";
	
}else{
	echo "No se ha Enviado Nada!";
}
?>