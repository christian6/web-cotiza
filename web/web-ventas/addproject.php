<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect();
	}
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Agragar Proyecto</title>
</head>
<body>
	
</body>
</html>
<?php
}else{
	redirect();
}
?>