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
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<script src="../modules/msgBox.js"></script>
	<style>
		#cont{
			text-align: center;
			width: 100%;
		}
		#csec{
			background-color: #2d2d2d;
			border-radius: 1em;
			color: #FFF;
			display: inline-table;
			height: 5em;
			margin: 0 1em 1em 0;
			padding: 1em;
			width: 10em;
		}
		#csub{
			background-color: #2d2d2d;
			border-radius: 1em;
			color: #FFF;
			display: inline-table;
			height: 3em;
			line-height: 3em;
			width: 100%;
		}
	</style>
</head>
<body>
<?php include ("includes/menu-manager.inc"); ?>
	<header></header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Inicio</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="manager-pro.php">Admin. Proyecto</a>
				<span class="divider">/</span>
			</li>
			<li class="active">Admin. Sectores</li>
		</ul>
	</div>
	<section>
		<?php
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT descripcion FROM ventas.proyectos WHERE proyectoid LIKE '".$_GET['pro']."'");
			if ($cn->num_rows($query) > 0) {
				$nom_pro = $cn->ExecuteNomQuery($query);
			}
			$cn->close($query);
			if ($_GET['sub'] != '') {
				$cn = new PostgreSQL();
				$query = $cn->consulta("SELECT subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$_GET['pro']."' AND subproyectoid LIKE '".$_GET['sub']."'");
				if ($cn->num_rows($query) > 0) {
					$nom_sub = $cn->ExecuteNomQuery($query);
				}
				$cn->close($query);
			}
		?>
		<div class="container well">
			<h3 class="t-info">Administrador de Proyecto <?php echo($nom_pro[0]); ?></h3>
			<h4 class="t-warning">Proyecto <?php echo($nom_pro[0]); ?></h4>
			<?php if ($_GET['sub'] != ''): ?>
				<h4 class="t-warning">Subproyecto <?php echo($nom_sub[0]); ?></h4>
			<?php endif ?>
			<div class="row show-grid">
				<div class="span8 well c-yellow">
					<div id="cont">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT * FROM ventas.sectores WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ORDER BY nroplano ASC");
							if ($cn->num_rows($query) > 0 ) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									?>
									<div id="csec">
										<a class='t-white' href="manager-detsec.php?pro=<?php echo $_GET['pro']; ?>&sub=<?php echo $_GET['sub']; ?>&sec=<?php echo $result['nroplano']; ?>">
										<i class="icon-list-alt icon-white"></i>
										<p>
											<?php echo $result['nroplano']; ?>	
										</p>
										<p><?php echo $result['sector']; ?></p>
										</a>
									</div>
									<?php
								}
							}
							$cn->close($query);
						?>
					</div>
				</div>
				<div class="span3 well c-yellow">
					<div id="cont">
						<?php
							$cn = new PostgreSQL();
							$query = $cn->consulta("SELECT * FROM ventas.subproyectos WHERE proyectoid LIKE '".$_GET['pro']."' ORDER BY subproyecto ASC");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									?>
									<div id="csub">
										<a class='t-white' href="?pro=<?php echo $_GET['pro']; ?>&sub=<?php echo $result['subproyectoid']; ?>">
											<p><?php echo $result['subproyecto']; ?></p>
										</a>
									</div>
									<?php
								}
							}
							$cn->close($query);
						?>
					</div>
				</div>
				<!--<div class="span8 well c-yellow">
					<div id="cont">
						
					</div>
				</div>-->
				<div class="span12">
					<div class="well c-blue-light t-info">
						<h4>Observaciones de Operaciones</h4>
						<div class="row show-grid">
							<div class="span12">
								<div class="span6 well">
									<?php
										$cn = new PostgreSQL();
										$query = $cn->consulta("SELECT id,to_char(fecha, 'HH24:MI DD/MM/YYYY') as fec,msg,tm  FROM ventas.alertapro WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ORDER BY fecha DESC");
										if ($cn->num_rows($query) > 0) {
											while ($result = $cn->ExecuteNomQuery($query)) {
												if ($result['tm'] == 'o') {
													echo "<div class='alert alert-warning span4 pull-right'>";
													echo "<strong>Operaciones <span class='pull-right'>".$result['fec']."</span></strong>";
													echo "<p>".$result['msg']."</p>";
													echo "</div>";
												}else if($result['tm'] == 'v'){
													echo "<div class='alert alert-success span4'>";
													echo "<strong>Ventas <span class='pull-right'>".$result['fec']."</span></strong>";
													echo "<p>".$result['msg']."</p>";
													echo "</div>";
												}else if($result['tm'] == 'a'){
													echo "<div class='alert alert-info span4' pull-center>";
													echo "<strong>Gerencia <span class='pull-right'>".$result['fec']."</span></strong>";
													echo "<p>".$result['msg']."</p>";
													echo "</div>";
												}
											}
										}
										$cn->close($query);
									?>
								</div>
								<div class="span5">
									<input type="hidden" id="pro" value="<?php echo $_GET['pro']; ?>">
									<input type="hidden" id="sub" value="<?php echo $_GET['sub']; ?>">
									<div class="well">
										<script>
											function onlineprobs () {
												$("#proobs").animate({height:"7em"},800);
											}
											function offlineprobs () {
												if ($("#proobs").val() == "") {
													$("#proobs").animate({height:"1.5em"},800);
												}
											}
											function publishing () {
												if ($("#proobs").val() != "") {
													var prm = {
														'tra' : 'msgplu',
														'pro' : $("#pro").val(),
														'sub' : $("#sub").val(),
														'msg' : $("#proobs").val(),
														'tfr' : 'a'
													}
													$.ajax({
														data : prm,
														url : 'includes/incsectores.php',
														type : 'POST',
														dataType : 'html',
														success : function (response) {
															if (response == 'success') {
																location.href='';
															}
														},
														error : function (obj,que,otr) {
															$.msgBox({
																title : 'Error',
																content : 'Si estas viendo esto es por que fallé',
																type : 'error',
																opacity : 0.6,
																autoClose : true
															});
														}
													});
												}else{

												}
											}
										</script>
										<h5>Escribe una observacion para este proyecto</h5>
										<div class="control-group">
											<div class="controls">
												<textarea name="proobs" onBlur="offlineprobs();" onFocus="onlineprobs();" id="proobs" style="width:96%;" maxlength="320" rows="1"></textarea>
											</div>
										</div>
										<div class="controls">
											<button class="btn btn-success t-d" onClick="publishing();"><i class="icon-comment"></i> Publicar</button>
											<small>Solo se admiten 320 caracteres.</small>
										</div>
									</div>
								</div>
								<div class="span5">
									<div class="row show-grid">
										<div class="span5">
											<div class="well c-blue-light t-info">
												<h4>Archivos Complementarios</h4>
												<?php
												function ListFolder($path)
												{	
													try {
														//using the opendir function
													    $dir_handle = @opendir($path) or die("Unable to open $path");
													    
													    //Leave only the lastest folder name
													    $dirname = end(explode("/", $path));
													    
													    //display the target folder.
													    echo ("<li><i class='icon-folder-open'></i> $dirname\n");
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
													                echo "<li><i class='icon-file'></i> <a href='".$path.$file."' target='_parent'>$file</a></li>";
													            }
													        }
													    }
													    echo "</ul>\n";
													    echo "</li>\n";
													    
													    //closing the directory
													    closedir($dir_handle);
													} catch (Exception $e) {
														echo $e->getMessage();
													}
												}
												try {
													if ($_GET['sub'] != '') {
														ListFolder("../project/".$_GET['pro']."/".$_GET['sub']."/comp/");
													}else{
														ListFolder('../project/'.$_GET['pro'].'/comp/');
													}
												} catch (Exception $e) {
													echo $e->getMessage();
												}
												?>
											</div>
										</div>
										<div class="span5">
											<div class="well c-blue-light t-info">
												<h4>Archivos Administrativos</h4>
												<?php
												if ($_GET['sub'] != '') {
													ListFolder("../project/".$_GET['pro']."/".$_GET['sub']."/adm/");
												}else{
													ListFolder("../project/".$_GET['pro']."/adm/");
												}
												//$adm = shell_exec($cmda);
												//echo php_file_tree($_SERVER['DOCUMENT_ROOT'], "javascript:alert('You clicked on [link]');");												
												?>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
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