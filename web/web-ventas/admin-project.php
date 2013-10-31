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
	<script src="js/project.js"></script>
	<link rel="stylesheet" href="../css/msgBoxLight.css">
	<script src="../modules/msgBox.js"></script>
	<script src="http://labs.abeautifulsite.net/archived/phpFileTree/demo/php_file_tree.js"></script>
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
			background-color: #CCC;
			border: 1px solid white;
			border-radius: 5px;
			box-shadow: 0 0 .8em #FFF;
			display: inline-block;
			margin: 5px;
			padding: 10px;
			width: 150px;
		}
	</style>
</head>
<body>
	<?php include ("includes/menu-ventas.inc"); ?>
	<?php
		$status = '';
		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT esid FROM ventas.proyectos WHERE proyectoid LIKE '".$_GET['id']."' LIMIT 1 OFFSET 0 ");
		if ($cn->num_rows($query) > 0) {
			while ($result = $cn->ExecuteNomQuery($query)) {
				$status = $result[0];
			}
		}
		$cn->close($query);

		$res = 0;
		$cn =  new PostgreSQL();
		$query = $cn->consulta("SELECT TRIM(e.empnom) ||' ' ||TRIM(e.empape) as n FROM ventas.proyectopersonal p
								INNER JOIN admin.empleados e
								ON p.empdni LIKE e.empdni
								WHERE p.proyectoid LIKE '".$_GET['id']."'");
		if ($cn->num_rows($query) > 0) {
			$result = $cn->ExecuteNomQuery($query);
			$responsable =  TRIM($result[0]);
			$res = 1;
		}
		$cn->close($query);

		$cn = new PostgreSQL();
		$query = $cn->consulta("SELECT descripcion FROM ventas.proyectos WHERE proyectoid LIKE '".$_GET['id']."'");
		if ($cn->num_rows($query) > 0) {
			$nom_pro = $cn->ExecuteNomQuery($query);
		}
		$cn->close($query);
		if ($_GET['sub'] != '') {
			$cn = new PostgreSQL();
			$query = $cn->consulta("SELECT subproyecto FROM ventas.subProyectos WHERE proyectoid LIKE '".$_GET['id']."' AND subproyectoid LIKE '".$_GET['sub']."'");
			if ($cn->num_rows($query) > 0) {
				$nom_sub = $cn->ExecuteNomQuery($query);
			}
			$cn->close($query);
		}
	?>
	<header>
		<input type="hidden" id="pro" value="<?php echo $_GET['id']; ?>">
		<input type="hidden" id="sub" value="<?php echo $_GET['sub']; ?>">
	</header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Inicio</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="proyecto.php">Proyecto</a>
				<span class="divider">/</span>
			</li>
			<?php if ($_GET['sub'] == ''): ?>
				<li class="active">admin-project</li>
			<?php else: ?>	
					<a href="admin-project.php?id=<?php echo $_GET['id'];?>">Admin Proyecto</a>
			<?php endif ?>
			
		</ul>
	</div>
	<section>
		<div class="container well">
		<div class="row show-grid">
			<div class="span8 well">
				<div class="row show-grid">
					<div class="span5">
						<h4 class="t-info">Administración de Proyecto <?php echo $nom_pro[0]; ?></h4>

						<h5 class="t-warning"> Nombre Proyecto : <?php echo $nom_pro[0]; ?></h5>
						<?php if( $_GET['sub'] != '') {?>
							<h5 class="t-warning"> Nombre Proyecto : <?php echo $nom_sub[0]; ?></h5>
						<?php } ?>

						<input type="hidden" id="txtproid" name="txtproid" value="<?php echo $_REQUEST['id']; ?>">
						<div class="btn-group">
							<button title="Nuevo Sector" class="btn btn-danger" onClick="showsector();" <?php if( $status == 55 || $status == 59) { echo "DISABLED"; }?> />
								<i class="icon-th"></i> 
								<span class="visible-desktop"><h6>Nuevo Sector</h6></span>
							</button>
							<!--<button class="btn btn-danger" onClick="showadicional();" <?php if( $status == 55 || $status == 59) { echo "DISABLED"; }?> DISABLED/>
								<i class="icon-th-list"></i>
								<span class="visible-desktop"><h6>Nuevo Adicional</h6></span>
							</button>-->
							<?php if ($_GET['sub'] == ''){ ?>
							<button title="Nuevo Subproyecto" class="btn btn-danger" onClick="showsubpro();" <?php if( $status == 55 || $status == 59) { echo "DISABLED"; }?> />
								<i class="icon-th-large"></i>
								<span class="visible-desktop"><h6>Nuevo Sub-Proyecto</h6></span>
							</button>
							<!--<button class="btn btn-danger" onClick="showuser();" <?php if($res == 1){ echo "DISABLED"; } ?> >
								<i class="icon-user"></i>
								<span class="visible-desktop"><h6>Responsable</h6></span>
							</button>-->
							<button class="btn btn-danger" onClick="showfiles();">
								<i class="icon-file"></i>
								<span class="visible-desktop"><h6>Archivos</h6></span>
							</button>
							<button class="btn btn-success" onClick="showconf();" <?php if( $status == 55 || $status == 59) { echo "DISABLED"; }?> />
								<i class="icon-ok"></i>
								<span class="visible-desktop"><h6>Confirmar</h6></span>
							</button>
							<?php } ?>
						</div>
						<hr class="hs">
					</div>
				</div>
				<div class="modal fade in hide c-yellow-light t-info" id="mfiles">
					<div class="modal-header">
						<a data-dismiss="modal" class="close">&times;</a>
						<h3>Subir Archivos</h3>
						<small>Documentos del proyecto <?php echo $_GET['id']; ?></small>
					</div>
					<div class="modal-body">
						<div class="row show-grid">
							<div class="span5">
								<div class="control-group">
									<label class="control-label">Documento Complementarios</label>
									<div id="bgfile1" class="controls well pull-center" style="background-color: #2D2D2D; border: .3em dashed #088ccc;">
										<a href="javascript:openfc();"><h5>Click Aqui</h5></a>
										<input type="file" class="hide" id="fc" onChange="changestyle('bgfile1');" accept="application/x-rar">
									</div>
								</div>
							</div>
							<div class="span5">
								<div class="control-group">
									<label class="control-label">Documento Administrativos</label>
									<div id="bgfile2" class="controls well pull-center" style="background-color: #2D2D2D; border: .3em dashed #088ccc;">
										<a href="javascript:openfa();"><h5>Click Aqui</h5></a>
										<input type="file" class="hide" onChange="changestyle('bgfile2');" id="fa" accept="application/x-rar">
									</div>
								</div>
							</div>
							<div class="span5">
								<button class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
								<button class="btn btn-info t-d pull-right" onClick="uploadfile();"><i class="icon-upload"></i> Subir Archivos</button>
							</div>
						</div>
					</div>
				</div>
				<section>
					<?php if( $status == 55 || $status == 59) {?>
					<div class="alert alert-block fade in alert-success">
						<strong>Aprobado</strong>
						<p>Este proyecto ha sido aprobado.</p>
					</div>
					<?php } 
					if ($res != 0) {
					?>
					<div class="well c-yellow-light t-warning">
						<strong>Responsable  </strong> <?php echo $responsable; ?>.
					</div>
					<?php } ?>
				</section>
				<div class="row show-grid">
					<div class="span8">
						<h5>Sectores</h5>
						<hr class="hs">
						<div id="cont">
						<?php
						$cn = new PostgreSQL();
						$sql = "SELECT nroplano,sector,descripcion FROM ventas.sectores WHERE ";
						if ($_GET['sub'] != "") {
							$sql .= "proyectoid LIKE '".$_GET['id']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ORDER BY nroplano asc";
						}else{
							$sql .= "proyectoid LIKE '".$_GET['id']."' AND TRIM(subproyectoid) LIKE '' ORDER BY nroplano asc";
						}
						$query = $cn->consulta($sql);
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
							?>
								<article>
									<a href="javascript:showesec('<?php echo $result['nroplano']; ?>','<?php echo $result['sector']; ?>','<?php echo $result['descripcion']; ?>');" class="close pull-left"><i class="icon-edit"></i></a>
									<a href="javascript:delsec('<?php echo $result['nroplano']; ?>');" class="close">&times;</a>
									<a id="txts" href="sectores.php?nropla=<?php echo $result['nroplano']; ?>&proid=<?php echo $_GET['id']; ?>&sub=<?php echo $_GET['sub']; ?>&status=<?php echo $status; ?>">
										<i class="icon-flag"></i>
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
							$query = $cn->consulta("SELECT proyectoid,subproyectoid,subproyecto,fecent,obser FROM ventas.subproyectos WHERE proyectoid LIKE '".$_REQUEST['id']."'");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<span>";
									echo "<a href=javascript:delsub('".$result['proyectoid']."','".$result['subproyectoid']."'); class='close'>&times;</a>";
									?>
									<a href="javascript:showsubedit('<?php echo $result['proyectoid']; ?>','<?php echo $result['subproyectoid'];?>','<?php echo $result['subproyecto']; ?>','<?php echo $result['fecent'];?>','<?php echo $result['obser'];?>');" class="close pull-left"><i class="icon-edit"></i></a>
									<?php
									echo "<a href='?id=".$_GET['id']."&sub=".$result['subproyectoid']."'>".$result['subproyecto']."</a>";
									echo "</span>";
								}
							}
							$cn->close($query);
							?>
						</div>
					</div>
				</div>
			</div>



			<!--
			AQUI VA LA ASIGNACION DE RESPONSABLE EN VENTAS
		-->
		<div id="msec" class="modal fade in hide">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h5>Agregar Sector de Proyecto</h5>
				<div id="sasu" class="alert alert-success fade in hide">
					<a href="#" data-dismiss="alert" class="close">&times;</a>
					<strong>¡Bien Hecho!</strong>
					<p>Se ha guardado correctamente el sector.</p>
				</div>
				<div id="sawa" class="alert alert-warning hide fade in">
					<a href="#" data-dismiss="alert" class="close">&times;</a>
					<strong>¡Oh dios mio!</strong> Mejor que lo compruebes tú mismo, existen campos vacios.
				</div>
				<div id="saer" class="alert alert-danger hide in">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>¡Oh no!</strong> Parece que tienes un error, por que no llama a soporte.
				</div>
			</div>
			<div class="modal-body">
				<form action="" method="POST" name="frmsec" id="frmsec">
				<div class="row show-grid">
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="t-info">Proyecto ID</label>
							<div class="controls">
								<input type="text" class="span2" id="spro" value="<?php echo $_GET['id']; ?>" DISABLED>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="t-info">SubProyecto ID</label>
							<div class="controls">
								<input type="text" class="span2" id="ssub" value="<?php echo $_GET['sub']; ?>" DISABLED>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="t-info">Nro o Codigo Plano</label>
							<div class="controls">
								<input type="text" class="span2" maxlength="20" id="snro">
							</div>
						</div>
					</div>
					<div class="span3">
						<div class="control-group info">
							<label for="controls" class="t-info">Descripción</label>
							<div class="controls">
								<input type="text" class="span3" id="sdes" maxlength="80">
							</div>
						</div>
					</div>
					<div class="span5">
						<div class="control-group info">
							<label for="controls" class="t-info">Observación</label>
							<div class="controls">
								<textarea name="sobs" id="sobs" class="span5" rows="4" maxlength="200"></textarea>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger t-d pull-left" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
				<button class="btn btn-primary" onClick="savesec();"><i class="icon-ok icon-white"></i> Guardar Sector</button>
			</div>
		</div>
		<!--
		Modificar y elimnar Sectores
		-->
		<div id="esec" class="modal fade in hide c-yellow-light t-warning">
			<div class="modal-header">
				<a data-dismiss="modal" class="close">&times;</a>
				<h3>Modificar Sector <span id="nsec"></span></h3>
			</div>
			<div class="modal-body">
				<div class="control-group warning">
					<label for="controls" class="control-label">Descripción</label>
					<div class="controls">
						<input type="text" id="msdes" class="span5">
					</div>
				</div>
				<div class="control-group warning">
					<label for="controls" class="control-label">Observación</label>
					<div class="controls">
						<textarea id="msobs" rows="4" class="span5"></textarea>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
						<button class="btn btn-warning t-d pull-right" onClick="esec();"><i class="icon-ok"></i> Guardar Cambios</button>
					</div>
				</div>
			</div>
		</div>
		<div id="meditsub" class="modal fade in hide c-yellow-light t-warning">
			<div class="modal-header">
				<a data-dismiss="modal" class="close">&times;</a>
				<h4>Modificar Subproyecto</h4>
				<input type="hidden" id="esubpro" value="">
				<input type="hidden" id="esubid" value="">
			</div>
			<div class="modal-body">
				<div class="row show-grid">
					<div class="span5">
						<div class="control-group">
							<label class="control-label">Subproyecto</label>
							<div class="controls">
								<input type="text" id="subpro" class="span5">
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group">
							<label for="controls" class="control-label">Fecha de Entrega</label>
							<div class="controls">
								<input type="text" id="msfec" class="span2" placeholder="aaaa-mm-dd">
							</div>
						</div>
					</div>
					<div class="span5">
						<div class="control-group">
							<label for="controls" class="control-label">Observación</label>
							<div class="controls">
								<textarea id="msuobs" rows="4" class="span5"></textarea>
							</div>
						</div>
					</div>
					<div class="span5">
						<button class="btn" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
						<button class="btn btn-warning pull-right" onClick="editsub();"><i class="icon-ok"></i> Guardar Cambios</button>
					</div>
				</div>
			</div>
		</div>
		<!--
		Fin
		-->
		<div id="msub" class="modal fade in hide">
			<div class="modal-header">
				<a href="" data=dismiss="modal" class="close">&times;</a>
				<h5>Agregar Sub-Proyecto</h5>
				<div id="uasu" class="alert alert-success fade in hide">
					<a href="#" data-dismiss="alert" class="close">&times;</a>
					<strong>¡Bien Hecho!</strong>
					<p>Se ha guardado correctamente el sector.</p>
				</div>
				<div id="uawa" class="alert alert-warning hide fade in">
					<a href="#" data-dismiss="alert" class="close">&times;</a>
					<strong>¡Oh dios mio!</strong> Mejor que lo compruebes tú mismo, existen campos vacios.
				</div>
				<div id="uaer" class="alert alert-danger hide in">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>¡Oh no!</strong> Parece que tienes un error, por que no llama a soporte.
				</div>
			</div>
			<div class="modal-body">
				<form action="" method="POST" mane="frmsub" id="frmsub">
				<div class="row show-grid">
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="t-info">Proyecto ID</label>
							<div class="controls">
								<input type="text" class="span2" id="upro" value="<?php echo $_GET['id']; ?>" DISABLED />
							</div>
						</div>
					</div>
					<div class="span3">
						<div class="control-group info">
							<label for="controls" class="t-info">Descripción SubProyecto</label>
							<div class="controls">
								<input type="text" class="span3" id="udes" />
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group info">
							<label for="controls" class="t-info">Fecha de Entrega</label>
							<div class="controls">
								<input type="text" class="span2" id="ufec" />
							</div>
						</div>
					</div>
					<div class="span5">
						<div class="control-group info">
							<label for="controls" class="t-info">Observación</label>
							<div class="controls">
								<textarea name="uobs" id="uobs" class="span5" rows="4"></textarea>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger pull-left" data-dismiss="modal" ><i class="icon-remove"></i> Cancelar</button>
				<button class="btn btn-primary" onClick="savesub();"><i class="icon-ok icon-white"></i> Guardar Subproyecto</button>
			</div>
		</div>
		<div id="madi" class="modal fade in hide">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h5>Agregar Adicional</h5>
				<div id="dasu" class="alert alert-success fade in hide">
					<a href="#" data-dismiss="alert" class="close">&times;</a>
					<strong>¡Bien Hecho!</strong>
					<p>Se ha guardado correctamente el sector.</p>
				</div>
				<div id="dawa" class="alert alert-warning hide fade in">
					<a href="#" data-dismiss="alert" class="close">&times;</a>
					<strong>¡Oh dios mio!</strong> Mejor que lo compruebes tú mismo, existen campos vacios.
				</div>
				<div id="daer" class="alert alert-danger hide in">
					<a href="#" class="close" data-dismiss="alert">&times;</a>
					<strong>¡Oh no!</strong> Parece que tienes un error, por que no llama a soporte.
				</div>
			</div>
			<div class="modal-body">
				<form action="" method="POST" mane="frmadi" id="frmadi">
					<div class="row show-grid">
						<div class="span2">
							<div class="control-group info">
								<label for="controls" class="t-info">Proyecto ID</label>							
								<div class="controls">
									<input type="text" class="span2" value="<?php echo $_GET['id']; ?>" id="dpro" DISABLED>
								</div>
							</div>
						</div>
						<div class="span2">
							<div class="control-group info">
								<label for="controls" class="t-info">Subproyecto ID</label>							
								<div class="controls">
									<input type="text" class="span2" value="<?php echo $_GET['sub']; ?>" id="dsub" DISABLED>
								</div>
							</div>
						</div>
						<div class="span2">
							<div class="control-group info">
								<label for="controls" class="t-info">Nro Plano</label>							
								<div class="controls">
									<input type="text" class="span2" id="dsec">
								</div>
							</div>
						</div>
						<div class="span4">
							<div class="control-group info">
								<label for="controls" class="t-info">Descripcion de Adicional</label>							
								<div class="controls">
									<input type="text" class="span4" value="" id="ddes">
								</div>
							</div>
						</div>
						<div class="span4">
							<div class="control-group info">
								<label for="controls" class="t-info">Observación</label>							
								<div class="controls">
									<textarea name="aobs" id="dobs" class="span4" rows="4"></textarea>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger pull-left" data-dismiss="modal"><i class="icon-remove"></i> Cancelar</button>
				<button class="btn btn-primary" onClick="saveadi();"><i class="icon-ok icon-white"></i> Guardar Adicional</button>
			</div>
		</div>
		<div id="mconf" class="modal fade in hide">
			<div class="modal-header c-blue-light">
				<a href="#" class="close" data-dismiss="modal">&times;</a>
				<h3 class="t-info">Aprobar y confirmar proyecto.</h3>
			</div>
			<div class="modal-body c-blue-light">
				<div class="control-group">
					<p class="t-info">
						Al parecer has teminado de llenar al detalle todos los sectores del proyecto.</p>
					<p class="t-info">Estas seguro(a) que deseas aprobar y confirmar el proyecto, una ves aprobado
						necesitaras de autorización para modificar el proyecto.</p>
					<p class="pull-center">
						<button class="btn btn-danger t-d" data-dismiss="modal"><i class="icon-remove-circle"></i> NO</button>
						<button class="btn btn-success t-d" onClick="validaproved();"><i class="icon-ok-circle"></i> SI</button>
					</p>
				</div>
			</div>
			<!--<div class="modal-footer">
				<button class="btn pull-left"><i class="icon-remove"></i></button>
				<button class="btn"><i class="icon-ok"></i></button>
			</div>-->
		</div>


			<!--
			<div class="span8 well">
				<h5>Adicionales del Proyecto</h5>
				<div id="cont">
					<?php
					/*$cn = new PostgreSQL();
					$query = $cn->consulta("SELECT * FROM ventas.adicionales WHERE esid LIKE '56' AND proyectoid LIKE '".$_GET['id']."' 
											AND TRIM(subproyectoid) LIKE TRIM('".$_GET['sub']."'); ");
					//echo "SELECT * FROM ventas.adicionales WHERE esid LIKE '56' AND proyectoid LIKE '".$_GET['id']."' AND TRIM(subproyectoid) LIKE TRIM('".$_GET['sub']."'); ";
					if ($cn->num_rows($query) > 0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<div id='ad'>".$result['descrip']."</div>";
						}
					}
					$cn->close($query);*/
					?>
				</div>
			</div>
			-->
			<div class="span6">
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
						} catch (Exception $e) {
							echo $e->getMessage();
						}
					}
					try {
						if ($_GET['sub'] != '') {
							ListFolder("../project/".$_GET['id']."/".$_GET['sub']."/comp/");
						}else{
							ListFolder('../project/'.$_GET['id'].'/comp/');
						}
					} catch (Exception $e) {
						echo $e->getMessage();
					}
					?>
				</div>
			</div>
			<div class="span6">
				<div class="well c-blue-light t-info">
					<h4>Archivos Administrativos</h4>
					<?php
					if ($_GET['sub'] != '') {
						ListFolder("../project/".$_GET['id']."/".$_GET['sub']."/adm/");
					}else{
						ListFolder("../project/".$_GET['id']."/adm/");
					}
					//$adm = shell_exec($cmda);
					//echo php_file_tree($_SERVER['DOCUMENT_ROOT'], "javascript:alert('You clicked on [link]');");
					

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