<?php
define('PGHOST','127.0.0.1'); //Cambiar Esta linea
define('PGPORT',5432);
define('PGDATABASE','bdicrweb');
define('PGUSER', 'postgres');
define('PGPASSWORD', 'fronhell');
define('PGCLIENTENCODING','LATIN1');
define('ERROR_ON_CONNECT_FAILED',' No se puede conectar con el servidor de DB ahora!');

class PostgreSQL
{
	private $conexion;
	private $total_consultas;

	public function PostgreSQL()
	{
		if (!isset($this->conexion)) {
			$this->conexion = (pg_pconnect('host=' . PGHOST . ' port=' . PGPORT . ' dbname=' . PGDATABASE . ' user=' . PGUSER . ' password=' . PGPASSWORD)
   or die('No pudo conectarse: ' . pg_last_error()));
		}
	}

	public function consulta($consulta)
	{
		$con = pg_pconnect('host=' . PGHOST . ' port=' . PGPORT . ' dbname=' . PGDATABASE . ' user=' . PGUSER . ' password=' . PGPASSWORD)
   or die('No pudo conectarse: ' . pg_last_error());
		$this->total_consultas++;
		$result = pg_query($con,$consulta);
		if (!$result) {
			echo 'PostgreSQL Error: '.pg_last_error();
			exit;
		}
		return $result;
	}

	public function ExecuteNomQuery($consulta)
	{
		return pg_fetch_array($consulta);
	}

	public function affected_rows($consulta)
	{
		return pg_affected_rows($consulta);
	}

	public function num_rows($consulta){
   		return pg_num_rows($consulta);
  	}

  	public function getTotalConsultas(){
   		return $this->total_consultas; 
  	}

  	public function close($consulta){
  		return pg_close($consulta);
  	}
}

?>
