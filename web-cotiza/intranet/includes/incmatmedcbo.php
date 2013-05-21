<? include ("../../datos/postgresHelper.php"); ?>

<?php

$nom = $_REQUEST['nom'];
if(isset($nom)){
?>
	<label>Seleccione la Medida: </label>
	<select id="matmed" name="matmed" onclick="dat();">
<?php
	$cn=new PostgreSQL();
	$query=$cn->consulta("SELECT matmed FROM admin.materiales WHERE matnom like '".$nom."'");
	if ($cn->num_rows($query)>0) {
		
		while ($result = $cn->ExecuteNomQuery($query)) {
			echo "<option value='".$result['matmed']."'>".$result['matmed']."</option>";
		}
	
	}
?>
	</select>
<?php
}else{
	echo"<label id='vacio'>No existe una Medida..</label>";
}
?>