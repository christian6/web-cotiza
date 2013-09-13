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
	<title>Administrar Detalle de Sector</title>
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
		#fullpdf{
			display: none;
			margin-top: 5em;
			position: absolute;
			/*top: 1em;*/
		}
		#fullscreen-icr button{
			position: absolute;
			top: 3em;
		}
		#plano{
			background-color: #2E3134;
			border: .3em dashed gray;
			border-radius: .3em;
			color: #7f858a;
			font-size: 1em;
			font-weight: bold;
			padding: .5em;
			text-align: center;
			text-transform: uppercase;
		}
	</style>
	<script>
		$(function () {
			resizesmall();
		});
		function resizesmall () {
			$( "#plano" ).animate({
				height: "2em"
			},1000);
			$("#vpdf").css('display','none');
		}
		function resizefull () {
			$( "#plano" ).animate({
				height: "31em"
			},1000);
			$( "#vpdf").css('display','block');
		}
		function openfull () {
			$( "#fullscreen-icr" ).show("clip",{},1600);
			$("#fullpdf").css('display','block');
		}
		function closefull () {
			$( "#fullscreen-icr" ).hide("clip",{},2000);
		}
	</script>
</head>
<body>
	<?php include ("includes/menu-ventas.inc"); ?>
	<header></header>
	<section>
		<div class="container well">
			<h3>Sector <?php echo $_GET['sec']; ?></h3>
			<?php
				$dir = "";
				$file = -1;
				if ($_GET['sub'] != '') {
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['pro']."/".$_GET['sub']."/".$_GET['sec'].".pdf")) {
						$dir = "/web/project/".$_GET['pro']."/".$_GET['sub']."/".$_GET['sec'].".pdf";	
						$file = 1;
					}
				}else{
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/web/project/".$_GET['pro']."/".$_GET['sec'].".pdf")) {
						$dir = "/web/project/".$_GET['pro']."/".$_GET['sec'].".pdf";
						$file = 1;
					}
				}
			?>
			<?php if ($file == 1){ ?>
			<div class="row show-grid">
				<div class="span12">
					<div id="plano">
						<div class="btn-group pull-left">
							<button class="btn" onClick="openfull();"><i class="icon-eye-open"></i></button>
							<button class="btn" onClick="resizesmall();"><i class="icon-resize-small"></i></button>
							<button class="btn" onClick="resizefull();"><i class="icon-resize-full"></i></button>
						</div>
						<iframe id="vpdf" src="<?php echo $dir; ?>" width="100%" height="400" frameborder="0"></iframe>
					</div>
				</div>
			</div>
			<?php } ?>
			<table class="table table-condensed table-hover table-bordered">
				<thead>
					<th></th>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Cantidad</th>
					<th>Stock</th>
					<th>Precio</th>
					<th>Importe</th>
				</thead>
				<tbody>
				<?php
					$cn = new PostgreSQL();
					$qsql = "";
					$import = 0;
					$total = 0;
					$qsql = "SELECT DISTINCT d.materialesid,m.matnom,m.matmed,m.matund,SUM(d.cant) as cant
							FROM operaciones.metproyecto d INNER JOIN admin.materiales m
							ON d.materialesid LIKE m.materialesid
							INNER JOIN ventas.proyectos p
							ON d.proyectoid LIKE p.proyectoid ";
					$qsql .= "WHERE d.proyectoid LIKE '".$_GET['pro']."' AND TRIM(subproyectoid) LIKE '".$_GET['sub']."' 
							AND TRIM(d.sector) LIKE TRIM('".$_GET['sec']."') GROUP BY d.materialesid,m.matnom,m.matmed,m.matund";
					$query = $cn->consulta($qsql);
					if ($cn->num_rows($query) > 0) {
						$i = 1;
						while ($result = $cn->ExecuteNomQuery($query)) {
							echo "<tr>";
							echo "<td id='tc'>".$i++."</td>";
							echo "<td>".$result['materialesid']."</td>";
							echo "<td>".$result['matnom']."</td>";
							echo "<td>".$result['matmed']."</td>";
							echo "<td id='tc'>".$result['matund']."</td>";
							echo "<td id='tc'>".$result['cant']."</td>";
							$c = new PostgreSQL();
							$q = $c->consulta("SELECT * FROM operaciones.sp_search_stock_mat('".$result['materialesid']."');");
							if ($c->num_rows($q) > 0) {
								$r = $c->ExecuteNomQuery($q);
								echo "<td id='tc'>".$r[0]."</td>";
								echo "<td id='tc'>".$r[1]."</td>";
								echo "<td style='text-align: right;'>".($result['cant'] * $r[1])."</td>";
							}else{
								echo "<td id='tc'>-</td>";
								echo "<td id='tc'>-</td>";
								echo "<td id='tc'>-</td>";
							}
							$c->close($q);
							$total += ($result['cant'] * $r[1]);

							//echo "<td id='tc'><a href='javascript:conedit(".$result['materialesid'].");'><i class='icon-pencil'></i></a></td>";
							//echo "<td id='tc'><a href='javascript:delmat(".$result['materialesid'].");'><i class='icon-remove'></i></a></td>";
							echo "</tr>";
						}
					}
			              					
					$cn->close();
				?>
				</tbody>
				<tfoot class="c-yellow-light">
					<td colspan="8" style="text-align: right;"><strong>Total</strong></td>
					<th style="text-align:right;"><?php echo $total; ?></th>
				</tfoot>
			</table>
			<div class="well c-yellow-light">
				<a href="" class="close">&times;</a>
			</div>
		</div>
	</section>
	<div id="fullscreen-icr" class="pull-center">
		<button class="btn btn-danger" onClick="closefull();"><i class="icon-remove"></i></button>
		<iframe id="fullpdf" src="<?php echo $dir; ?>" width="100%" height="90%" frameborder="0">
		</iframe>
	</div>
	<div class="" id="space"></div>
	<footer></footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>