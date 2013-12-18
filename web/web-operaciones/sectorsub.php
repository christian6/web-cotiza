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
	<title>Sectores y Subproyectos</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="../css/styleint.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<link rel="stylesheet" href="../modules/msgBox.js">
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<style>
		.cont{
			text-align: center;
		}
		.cont span{
			background-color: #CCC;
			border-radius: .8em;
			display: block;
			padding: .6em;
		}
		.cont #car article{
			/*background-color: #084B8A;*/
			border: 3px dashed #2d2d2d;
			border-radius: 50% 75% 50%;
			display: block;
			margin: 5px;
			padding: 10px;
			width: 150px;
		}
		.cont article a{
			color: #333;
		}
	</style>
</head>
<body>
	<?php include ("includes/menu-operaciones.inc"); ?>
	<header></header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Home</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="aprobados.php">Proyecto Aprobados</a>
				<span class="divider">/</span>
			</li>
			<li class="active">admin-project</li>
		</ul>
	</div>
	<section>
		<div class="container well">
			<h4>Sectores y Subproyectos</h4>
			<a href="aprobados.php" class="btn btn-success t-d"><i class="icon-arrow-left"></i> Volver</a>
			<hr>
			<div class="row show-grid">
				<div class="span8 well c-gd">
					<div class="cont">
						<center>
						<?php
							$cn = new PostgreSQL();
							$sql = "SELECT nroplano,sector,descripcion FROM ventas.sectores WHERE ";
							if ($_GET['sub'] != "") {
								$sql .= " proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."'";
							}else{
								$sql .= " proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE ''";
							}
							$query = $cn->consulta($sql);
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
								?>
								<div id="car" class="span2">
									<article class="c-orange">
										<a id="txts" href="pedidosector.php?sec=<?php echo $result['nroplano']; ?>&pro=<?php echo $_GET['pro']; ?>&sub=<?php echo TRIM($_GET['sub']); ?>">
											<i class="icon-map-marker"></i>
											<label for="label"><?php echo $result['nroplano']; ?></label>
											<label for="label"><?php echo $result['sector']; ?></label>	
										</a>
									</article>
									<div class="progress progress-success progress-striped active c-yellow" style="height: .5em;">
										<?php
											$c =  new PostgreSQL();
											$s = "SELECT COUNT(DISTINCT materialesid),(select count(flag) from  operaciones.metproyecto where flag like '0' AND 
												proyectoid LIKE '".$_GET['pro']."' AND 
												TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$result['nroplano']."')
												from operaciones.metproyecto WHERE proyectoid LIKE '".$_GET['pro']."' AND 
												TRIM(subproyectoid) LIKE '".$_GET['sub']."' AND TRIM(sector) LIKE '".$result['nroplano']."'";
											$q = $c->consulta($s);
											if ($c->num_rows($q) > 0) {
												$re = $c->ExecuteNomQuery($q);
												$por = (($re[1] * 100)/ $re[0]);
											}
											$c->close($q);
										?>
										<div class="bar" style="width: <?php echo $por;?>%;"></div>
									</div>
								</div>
								<?php
								}
							}
							$cn->close($query);
						?>
					</center>
					</div>	
				</div>
				<div class="span3 well c-g">
					<!--<h5 class="t-orange">Sub Proyectos</h5>-->
						<div class="cont">
							<?php
							$cn = new PostgreSQL();							
							$query = $cn->consulta("SELECT DISTINCT subproyectoid,subproyecto FROM ventas.subproyectos WHERE  proyectoid LIKE '".$_GET['pro']."'");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<span><a href='?pro=".$_GET['pro']."&sub=".$result['subproyectoid']."'>".$result['subproyecto']."</a></span>";
								}
							}
							$cn->close($query);
							?>
						</div>
				</div>
				<div class="span12">
					<div class="well c-blue-light t-info">
						<h4>Observaciones de Operaciones</h4>
						<div class="row show-grid">
							<div class="span12">
								<div class="span6 well">
									<?php
										$cn = new PostgreSQL();
										$query = $cn->consulta("SELECT id,to_char(fecha, 'HH24:MI DD/MM/YYYY') as fec,msg,tm FROM ventas.alertapro 
											WHERE proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ORDER BY fecha DESC");
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
													echo "<div class='alert alert-info span4'>";
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
														'tfr' : 'o'
													}
													$.ajax({
														data : prm,
														url : 'includes/incproyecto.php',
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

									<div class="span5">
									<div class="row show-grid">
										<div class="span4">
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
													                echo "<li><i class='icon-file'></i> <a href='".$path.$file."' target='_blank'>$file</a></li>";
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
										<!--<div class="span4">
											<div class="well c-blue-light t-info">
												<h4>Archivos Administrativos</h4>
												<?php/*
												if ($_GET['sub'] != '') {
													ListFolder("../project/".$_GET['pro']."/".$_GET['sub']."/adm/");
												}else{
													ListFolder("../project/".$_GET['pro']."/adm/");
												}
												//$adm = shell_exec($cmda);
												//echo php_file_tree($_SERVER['DOCUMENT_ROOT'], "javascript:alert('You clicked on [link]');");												
												*/?>
											</div>
										</div>-->
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
	<footer></footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>