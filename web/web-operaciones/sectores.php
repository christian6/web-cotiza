<?php
include ("../includes/session-trust.php");
if (sesaccess() == 'ok') {
  if (sestrust('k') == 0) {
    redirect();
  }
include ("../datos/postgresHelper.php");
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Administrador de Proyectos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script src="js/proyectos.js"></script>
	<script src="../modules/msgBox.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<style>
		#txts{
			color: #000;
			font-weight: bold; 
			text-decoration: none;
		}
		#cont{
			background-color: gray;
			border-radius: .8em;
			padding: 18px;
			text-align: center;
		}
		#cont span{
			background-color: #CCC;
			border-radius: 5px;
			display: block;
			margin: 2px;
			padding: 10px;
			
		}
		#cont article, #ad{
			/*background-color: #CCC;*/
			border: 1px solid white;
			border-radius: 5px;
			display: inline-block;
			margin: 5px;
			padding: 10px;
			width: 150px;
		}
	</style>
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<?php
		$res = 0;
		$cn =  new PostgreSQL();
		$query = $cn->consulta("SELECT TRIM(e.empnom) ||' ' ||TRIM(e.empape) as n FROM ventas.proyectopersonal p
								INNER JOIN admin.empleados e
								ON p.empdni LIKE e.empdni
								WHERE p.proyectoid LIKE '".$_REQUEST['proid']."'");
		if ($cn->num_rows($query) > 0) {
			$result = $cn->ExecuteNomQuery($query);
			$responsable =  TRIM($result[0]);
			$res = 1;
		}
		$cn->close($query);
	?>
	<header></header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Home</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="proyecto.php">Proyectos</a>
				<span class="divider">/</span>
			</li>
			<li class="active">Sectores</li>
		</ul>
	</div>
	<section>
		<div class="container well">
		<div class="row show-grid">
			<div class="span8 well">
				<div class="row show-grid">
					<div class="span5">
						<h4>Administración de Proyectos</h4>
						<input type="hidden" id="txtproid" name="txtproid" value="<?php echo $_REQUEST['proid']; ?>">
					</div>
				</div>
				<div class="well c-yellow-light t-warning">
					<strong>Responsable  </strong><?php echo $responsable; ?>.
					<p>
						Solo si has terminado de revisar todos los planos y estas deacuerdo
						con el metrado planteado, aprueba el proyecto para poder realizar
						los pedidos al almacén.
						<input type="hidden" id="pro" value="<?php echo $_GET['proid']; ?>" />
					</p>
					<button class="btn btn-warning t-d" onClick="aproproop();"><i class="icon-ok"></i> Aprobar</button>
				</div>
				<div class="row show-grid">
					<div class="span8">
						<h5>Sectores</h5>
						<hr class="hs">
						<div id="cont">
						<?php
						$cn = new PostgreSQL();
						$sql = "SELECT nroplano,sector,descripcion,esid FROM ventas.sectores WHERE ";
						if ($_GET['sub'] != "") {
							$sql .= "proyectoid LIKE '".$_REQUEST['proid']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ";
						}else{
							$sql .= "proyectoid LIKE '".$_REQUEST['proid']."' AND TRIM(subproyectoid) LIKE '' ";
						}
						$query = $cn->consulta($sql);
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
								if ($result['esid'] == '60') {
									echo "<article class='c-green'>";
								}else{
							?>
								<article class="c-yellow-light">
							<?php } ?>
									<a id="txts" href="detsectores.php?nropla=<?php echo $result['nroplano']; ?>&proid=<?php echo $_REQUEST['proid']; ?>&es=<?php echo $result['esid']; ?>">
										<?php if ($result['esid'] == '60') { ?><i class="icon-ok"></i><?php }else{ echo "<i class='icon-flag'></i>"; } ?>
										<label for="label"><?php echo $result['nroplano']; ?></label>
										<label for="label"><?php echo $result['sector']; ?></label>	
									</a>
								</article>
							<?php
							}
						}
						$cn->close($query);
						?>
						</div>
					</div>
				</div>
			</div>
			<div class="span3 well">
				<div class="row show-grid">
					<div class="span3">
						<h5>Sub Proyectos</h5>
						<hr class="hs">
						<div id="cont">
							<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT subproyectoid,subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$_REQUEST['proid']."'");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<span><a href='?proid=".$_GET['proid']."&sub=".$result['subproyectoid']."'>".$result['subproyecto']."</a></span>";
								}
							}
							$cn->close($query);
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="span8 well">
				<h5>Adicionales del Proyecto</h5>
				<div id="cont">
					<?php
					$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT * FROM ventas.adicionales WHERE esid LIKE '56' AND proyectoid LIKE '".$_GET['proid']."' 
											AND TRIM(subproyectoid) LIKE TRIM('".$_GET['sub']."'); ");
					//echo "SELECT * FROM ventas.adicionales WHERE esid LIKE '56' AND proyectoid LIKE '".$_GET['id']."' AND TRIM(subproyectoid) LIKE TRIM('".$_GET['sub']."'); ";
					if ($cn->num_rows($query) > 0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<div id='ad' class='c-yellow-light'>".$result['descrip']."</div>";
						}
					}
					$cn->close($query);
					?>
				</div>
			</div>
			<div class="span6">
				<div class="well c-blue-light t-info">
					<h4>Archivos Complementarios</h4>
					<?php
					function ListFolder($path)
					{
					    //using the opendir function
					    $dir_handle = @opendir($path) or die("Unable to open $path");
					    
					    //Leave only the lastest folder name
					    $dirname = end(explode("/", $path));
					    
					    //display the target folder.
					    echo ("<li>$dirname\n");
					    echo "<ul>\n";
					    while (false !== ($file = readdir($dir_handle))) 
					    {
					        if($file!="." && $file!="..")
					        {
					            if (is_dir($path."/".$file))
					            {
					                //Display a list of sub folders.
					                ListFolder($path."/".$file);
					            }
					            else
					            {
					                //Display a list of files.
					                echo "<li>$file</li>";
					            }
					        }
					    }
					    echo "</ul>\n";
					    echo "</li>\n";
					    
					    //closing the directory
					    closedir($dir_handle);
					}

					if ($_GET['sub'] != '') {
						ListFolder("../project/".$_GET['proid']."/".$_GET['sub']."/comp/");
					}else{
						ListFolder('../project/'.$_GET['proid'].'/comp/');
					}
					
					?>
				</div>
			</div>
		</div>
		</div>
	</section>
	<div id="space"></div>
	<footer>
	</footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>