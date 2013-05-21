<!DOCTYPE html>
<?php 
session_start();
include ("../datos/postgresHelper.php");
$msg = ""; 
if (isset($_POST['btnnew'])) {
	if ($_POST['txtpwdpro'] == $_POST['txtpwdcon']) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("INSERT INTO public.loginpro VALUES('".$_POST['txtruc']."','".$_POST['txtusrpro']."','".$_POST['txtpwdpro']."')");
		$cn->affected_rows($query);
		$cn->close($query);
		$msg = "La Operación se Realizo Correctamente.";
	}else{
		$msg = "Las Contraseñas no Coinciden";
	}
}
if (isset($_POST['btnchange'])) {
	if ($_POST['txtpwdc'] == $_POST['txtpwdnc']) {
		$cn = new PostgreSQL();
		$query = $cn->consulta("UPDATE public.loginpro SET passwd = '".$_POST['txtpwdnc']."' WHERE rucproveedor ='".$_POST['txtruc']."'");
		$cn->affected_rows($query);
		$cn->close($query);
		$msg = "La Operación se Realizo Correctamente.";
	}else{
		$msg = "Las Contraseñas no Coinciden";
	}
}
?>
<html>
<head>
	<meta charset='utf-8' />
	<title>Login Proveedores</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../css/style-loginpro.css">
	<script type="text/javascript">
		function mostrar (obj) {
		if (obj == 1) {
			var clase = document.getElementById("changepwd");
			clase.style.display = 'block';
		}else if(obj == 0){
			var clase = document.getElementById("changepwd");
			clase.style.display = 'none';
		}
		}
	</script>
</head>
<body>
<header>
			<hgroup>
			<img src="../source/icrlogo.png">
			<div id="cab">
				<h1>Especialistas en Sistemas Contra Incendios</h1>
			</div>
		</hgroup>
</header>
<div id="sess">
<?php
$nom = $_SESSION['nom-icr'];
$car = $_SESSION['car-icr'];
?>
<p>
<label for="user" style="font-weight: bold;">Cargo:</label>
<?echo $car;?>&nbsp;
<label for="nom" style="font-weight: bold;">Nombre: </label>
<?echo $nom;?>
</p>
<p>
<label style="font-weight: bold;">Dni:</label>
&nbsp;<?echo $_SESSION['dni-icr']?>&nbsp;
<label style="font-weight: bold;">User:</label>
<?echo $_SESSION['user-icr'];?>
<button id="btnclose" class="btn btn-mini btn-primary" onclick="javascript:document.location.href = '../../web/includes/session-destroy.php';"> <i class="icon-lock"></i> Cerrar Session</button>
</p>
</div>
<?php 
if($_SESSION['accessicr']==true) { ?>
	<section>
		<?php include("includes/menu.inc");?>
		<hgroup>
			<h3> Login Proveedores</h3>
		</hgroup>
		<br>
		<form name="frmpro" method="POST" action="">
			<label for="lblpro">Seleccione: </label>
			<select name="cbopro" id="cbopro" onchange="this.form.submit()">
		<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT rucproveedor,razonsocial FROM admin.proveedor ORDER BY razonsocial ASC");
			if ($cn->num_rows($query)>0) {
				while ($result = $cn->ExecuteNomQuery($query)) {
					echo "<option value='".$result['rucproveedor']."'>".$result['razonsocial']."</option>";
				}
			}
			$cn->close($query);
		?>
			</select>
		</form>
		<br>
		<?php
			if (isset($_POST['cbopro'])) {
				?>
				<div id="cont">
					<?
						$cn = new PostgreSQL();
						$query = $cn->consulta("SELECT COUNT(*) FROM public.loginpro WHERE rucproveedor LIKE '".$_POST['cbopro']."'");
						if ($cn->num_rows($query)>0) {
							$result = $cn->ExecuteNomQuery($query);
							$cn->close($query);
							if ($result[0] == 1) {
								$cn = new PostgreSQL();
								$query = $cn->consulta("SELECT p.rucproveedor,p.razonsocial,l.username FROM admin.proveedor p INNER JOIN public.loginpro l ON p.rucproveedor=l.rucproveedor WHERE p.rucproveedor LIKE '".$_POST['cbopro']."' ORDER BY p.razonsocial ASC");
								if ($cn->num_rows($query)>0) {
									while ($result = $cn->ExecuteNomQuery($query)) {
										echo "<p><label>Nro de Ruc:</label>";
										echo "".$result['rucproveedor']."</p>";
										echo "<p><label>Razón Social:</label>";
										echo "".$result['razonsocial']."</p>";
										echo "<p><label>User Name:</label>";
										echo "".$result['username']."</p>";
										echo "<p><button type='Button' title='Cambiar Password' onClick='mostrar(1);'><img src='../source/changepwd48.png' /></button></p>";
										echo "<form name='frmchange' method='POST' action=''>";
									}
								}
								echo "<spam id='changepwd'>";
								echo "<input type='hidden' id='txtruc' name='txtruc' value=".$_POST['cbopro'].">";
								echo "<table>";
								echo "<tr>";
								echo "<td><label>Nuevo Password:</label></td>";
								echo "<td><input type='Password' id='txtpwdc' name='txtpwdc' title='Ingrese Nuevo Password' placeholder='Nuevo Password' REQUIRED/></td>";
								echo "</tr>";
								echo "<tr>";
								echo "<td><label>Confirmar Password:</label></td>";
								echo "<td><input type='Password' id='txtpwdnc' name='txtpwdnc' title='Comfirmar Nuevo Password' placeholder='Confirmar Password' REQUIRED/></td>";
								echo "</tr>";
								echo "</table>";
								echo "<button type='Button' title='Cancelar' onClick='mostrar(0);'><img src='../source/cancelar32.png' /></button>";
								echo "<button type='Submit' name='btnchange' title='Guardar Nuevo Password'><img src='../source/floppy32.png' /></button>";
								echo "</spam>";
								echo "</form>";
							}else if($result[0] == 0){
								?>
								<form name="frmlog" method="POST" action="">
									<p><label>Nro de Ruc: </label><?php echo $_POST['cbopro'];?></p>
									<input type="hidden" id="txtruc" name="txtruc" value="<?php echo $_POST['cbopro'];?>">
									<table>
										<tbody>
											<tr>
												<td><label>Username:</label></td>
												<td><input type="text" maxlenght="16" id="txtusrpro" name="txtusrpro" title="Ingrese el Usuario para el Proveedor" placeholder="Username" REQUIRED /></td>
											</tr>
											<tr>
												<td><label>Password:</label></td>
												<td><input type="Password" id="txtpwdpro" name="txtpwdpro" maxlenght="16" title="Ingrese Password para el Proveedor" placeholder="*******" REQUIRED></td>
											</tr>
											<tr>
												<td><label>Confirmar:</label></td>
												<td><input type="Password" id="txtpwdcon" name="txtpwdcon" maxlenght="16" title="Ingrese la confimacion del Password para el Proveedor" placeholder="*******" REQUIRED></td>
											</tr>
										</tbody>
									</table>
									<button type='Submit' name='btnnew' title='Guardar Nuevo Login'><img src='../source/floppy32.png' /></button>
								</form>
								<?php
							}
						}
					?>
				</div>
				<?php
			}
		?>
		<label id='msg'><?php echo $msg;?></label>
</section>
<?php 
}
?>
<footer>
</footer>
</body>
</html>
