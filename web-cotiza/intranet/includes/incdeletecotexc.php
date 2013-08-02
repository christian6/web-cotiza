<?php

include("../../datos/postgresHelper.php");

if ($_REQUEST['or'] == 'ok')
{

$cn = new PostgreSQL();
$query = $cn->consulta("DELETE FROM logistica.tmpcantpro");
$cn->affected_rows($query);
$cn->close($query);

$dir = "../../tmp/";

$handle = opendir($dir);
while ($file = readdir($handle))
{
   if (is_file($dir.$file))
   {
       unlink($dir.$file);
   }
}

echo "ok";
}

?>