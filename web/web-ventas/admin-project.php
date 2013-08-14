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
	?>
	<header></header>
	<div id="misub">
		<ul class="breadcrumb well">
			<li>
				<a href="index.php">Home</a>
				<span class="divider">/</span>
			</li>
			<li>
				<a href="proyecto.php">Proyecto</a>
				<span class="divider">/</span>
			</li>
			<li class="active">admin-project</li>
		</ul>
	</div>
	<section>
		<div class="container well">
		<div class="row show-grid">
			<div class="span8 well">
				<div class="row show-grid">
					<div class="span5">
						<h4>Administración de Proyectos</h4>
						<input type="hidden" id="txtproid" name="txtproid" value="<?php echo $_REQUEST['id']; ?>">
						<div class="btn-group">
							<button title="Nuevo Sector" class="btn btn-danger" onClick="showsector();" <?php if( $status == 55 || $status == 59) { echo "DISABLED"; }?> />
								<i class="icon-th"></i> 
								<span class="visible-desktop"><h6>Nuevo Sector</h6></span>
							</button>
							<button class="btn btn-danger" onClick="showadicional();" <?php if( $status == 55 || $status == 59) { echo "DISABLED"; }?> />
								<i class="icon-th-list"></i>
								<span class="visible-desktop"><h6>Nuevo Adicional</h6></span>
							</button>
							<button title="Nuevo Subproyecto" class="btn btn-danger" onClick="showsubpro();" <?php if( $status == 55 || $status == 59) { echo "DISABLED"; }?> />
								<i class="icon-th-large"></i>
								<span class="visible-desktop"><h6>Nuevo Sub-Proyecto</h6></span>
							</button>
							<!--<button class="btn btn-danger" onClick="showuser();" <?php if($res == 1){ echo "DISABLED"; } ?> >
								<i class="icon-user"></i>
								<span class="visible-desktop"><h6>Responsable</h6></span>
							</button>-->
							<button class="btn btn-success" onClick="showconf();" <?php if( $status == 55 || $status == 59) { echo "DISABLED"; }?> />
								<i class="icon-ok"></i>
								<span class="visible-desktop"><h6>Confirmar</h6></span>
							</button>
						</div>
						<hr class="hs">
					</div>
				</div>
				<section>
					<?php if( $status == 55 || $status == 59) {?>
					<div class="alert alert-block fade in alert-success">
						<strong>Aprobado</strong>
						<p>Este proyecto ha sido aprobado.</p>
					</div>
					<?php } ?>
					<div class="well c-yellow-light t-warning">
						<strong>Responsable  </strong> <?php echo $responsable; ?>.
					</div>
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
							$sql .= "proyectoid LIKE '".$_GET['id']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' ";
						}else{
							$sql .= "proyectoid LIKE '".$_GET['id']."' AND TRIM(subproyectoid) LIKE '' ";
						}
						$query = $cn->consulta($sql);
						if ($cn->num_rows($query) > 0) {
							while ($result = $cn->ExecuteNomQuery($query)) {
							?>
								<article>
									<a id="txts" href="sectores.php?nropla=<?php echo $result['nroplano']; ?>&proid=<?php echo $_GET['id']; ?>&sub=<?php echo $_GET['sub']; ?>">
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
							$query = $cn->consulta("SELECT subproyectoid,subproyecto FROM ventas.subproyectos WHERE proyectoid LIKE '".$_REQUEST['id']."'");
							if ($cn->num_rows($query) > 0) {
								while ($result = $cn->ExecuteNomQuery($query)) {
									echo "<span><a href='?id=".$_GET['id']."&sub=".$result['subproyectoid']."'>".$result['subproyecto']."</a></span>";
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
					$query = $cn->consulta("SELECT * FROM ventas.adicionales WHERE esid LIKE '56' AND proyectoid LIKE '".$_GET['id']."' 
											AND TRIM(subproyectoid) LIKE TRIM('".$_GET['sub']."'); ");
					//echo "SELECT * FROM ventas.adicionales WHERE esid LIKE '56' AND proyectoid LIKE '".$_GET['id']."' AND TRIM(subproyectoid) LIKE TRIM('".$_GET['sub']."'); ";
					if ($cn->num_rows($query) > 0) {
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<div id='ad'>".$result['descrip']."</div>";
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
						Al parecer has teminado de llenar al detalle todo el proyecto.</p>
					<p class="t-info">Estas seguro(a) que deseas aprobar y confirmar el proyecto, una ves aprobado
						necesitaras de confirmación para modificar el proyecto.</p>
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
		<input type="hidden" id="pro" value="<?php echo $_GET['id']; ?>">
		<input type="hidden" id="sub" value="<?php echo $_GET['sub']; ?>">
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