<?php
session_start();

$_SESSION = array();
$session_name = session_name();
session_destroy();

if ($_REQUEST['int'] == "t") {
	echo '<meta http-equiv="refresh" content="0;url=../intranet/index.php">';
}else{
echo "destroy";
}
?>