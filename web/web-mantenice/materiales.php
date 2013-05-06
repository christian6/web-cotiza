<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
	if (sestrust('k') == 0) {
		redirect(1);
	}
include ("../datos/postgresHelper.php");
/* Zone of Functions */

if ($_POST['btnsave'] == "btnsave") {
	$cn = new PostgreSQL();
	$query = $cn->consulta("INSERT INTO admin.materiales VALUES('".$_POST['txtid']."','".$_POST['txtnom']."','".$_POST['txtmed']."','".$_POST['cbound']."',".$_POST['txtprecio'].",'".$_POST['txtmar']."','".$_POST['txtmod']."','".$_POST['txtacab']."',".$_POST['txtarea'].")");
	$cn->affected_rows($query);
	$cn->close($query);
}

?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Mantenimientos de Materiales</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="../css/styleint.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap-responsive.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap.js"></script>
	<script>
		function view() {
			$("#myModal").modal('show');
		}
		function radio () {
			var c = document.getElementById("rbtnc");
			var d = document.getElementById("rbtnd");
			//alert(c.checked);
			if (c.checked) {
				document.getElementById("txtcod").disabled = false;
				document.getElementById("txtdes").disabled = true;
			}else if(d.checked){
				document.getElementById("txtcod").disabled = true;
				document.getElementById("txtdes").disabled = false;
			}
		}
		function actionDelete (id) {
			if (id != "") {
				if (confirm("Realmente Desea Eliminar el Material??")) {
					xmlhttp = new peticion();
					xmlhttp.onreadystatechange = function () {
						alert(xmlhttp.responseText);
						if (xmlhttp.readyState ==4 && xmlhttp.status == 200) {
							if(xmlhttp.responseText == "hecho"){
								document.getElementById("als").style.display = 'block';
								setTimeout(function() { location.href = '' }, 3000);
							}
						}
					}
					var requestUrl = '';
					requestUrl = "includes/incmateriales.php"+"?tra=del"+"&matid="+encodeURIComponent(id);
					xmlhttp.open("POST",requestUrl,true);
					xmlhttp.send();
				}
			}
		}
		function peticion(){
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			return xmlhttp;
		}
	</script>
</head>
<body>
<header>
</header>
<section>
	<div class="container well">
		<h3>Materiales</h3>
		<form action="" method="POST">
		<div class="row show-grid">
			<div class="span8">
				<div class="span6">
					<div class="btn-toolbar">
						<div class="btn-group">
							<button type="Button" class="btn btn-primary" onClick="view();"><i class="icon-file"></i> Nuevo</button>
							<button type="Submit" class="btn btn-info" name="btns" value="btns"><i class="icon-search"></i> Buscar</button>
							<button type="Button" class="btn btn-success" onClick="javascript:location.href='';"><i class="icon-refresh"></i> Refresh</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row show-grid">
				<div class="span3 offset1">
					<div class="control-group">
						<label class="radio control-label inline">
							<input type="radio" name="rbtn" value="c" id="rbtnc" onChange="radio();"> por Codigo
						</label>
						<label class="radio control-label inline">
							<input type="radio" name="rbtn" value="d" id="rbtnd" onChange="radio();"> por Descripción
						</label>
					</div>
				</div>
		</div>
		<div class="row show-grid">
			<div class="span3 offset1">
				<label for="" class="help-inline">Codigo</label>
				<input type="text" class="span2 inline" name="txtcod" title="Ingrese Codigo" id="txtcod" DISABLED REQUIRED/>
			</div>
			<div class="span6">
				<label for="" class="help-inline">Descripcion</label>
				<input type="text" class="span5 inline" name="txtdes" title="Ingrese Descripcion" id="txtdes" DISABLED REQUIRED/>
			</div>
		</div>
		</form>
		<hr>
		<div id="als" class="alert alert-success alert-block hide">
			<a class="close" data-dismiss="alert">×</a>
			<h4 class="alert-heading">Bien Hecho!!!</h4>
			Los cambios se han realizado correctamente.
		</div>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Item</th>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Medida</th>
					<th>Unidad</th>
					<th>Eliminar</th>
				</tr>
			</thead>
			<tbody>
		<?php
			if ($_POST['btns'] == 'btns') {
				$cn =  new PostgreSQL();
				$qsql = "SELECT materialesid,matnom,matmed,matund FROM admin.materiales ";
				if ($_POST['rbtn'] == "c") {
					$qsql .= " WHERE materialesid LIKE '".$_POST['txtcod']."%'";
				}elseif ($_POST['rbtn'] == "d") {
					$qsql .= " WHERE lower(matnom) LIKE lower('%".$_POST['txtdes']."%') ";
				}
				$query = $cn->consulta($qsql." ORDER BY matnom ASC");
				if ($cn->num_rows($query) > 0) {
					$i = 1;
					while ($result = $cn->ExecuteNomQuery($query)) {
						echo "<tr>";
						echo "<td style='text-align: center;'>".$i++."</td>";
						echo "<td style='text-align: center;'>".$result['materialesid']."</td>";
						echo "<td>".$result['matnom']."</td>";
						echo "<td>".$result['matmed']."</td>";
						echo "<td style='text-align: center;'>".$result['matund']."</td>";
						echo "<td style='text-align: center;'><a href='javascript:actionDelete(".$result['materialesid'].");'><i class='icon-trash'></i></a></td>";
						echo "</tr>";
					}
				}else{
					echo "<div class='alert alert-warning'>
								<a class='close' data-dismiss='alert'>x</a>
								<b>¡Atención!</b> Esta alerta necesita tu atención, pero no es muy importante.
								<h4>No se encontraron resultados</h4>
								</div>";
				}
				$cn->close($query);
			}
		?>
			</tbody>
		</table>
	</div>
	<div id="myModal" class="modal hide fade">
		<form name="frm1" method="POST" action="">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h3>Nuevo Material</h3>
        </div>
        <div class="modal-body">
				<div class="control-group">
					<label for="" class="label label-info">Codigo</label>
					<div class="controls">
						<input type="text" id="txtid" name="txtid" title="Ingrese el Codigo del Material" placeholder="codigo" class="span2" REQUIRED />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="label label-info">Nombre o Descripción</label>
					<div class="controls">
						<input type="text" id="txtnom" name="txtnom" class="span5" title="Nombre o descripcion" placeholder="Descripcion" REQUIRED />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="label label-info">Medida</label>
					<div class="controls">
						<input type="text" id="txtmen" name="txtmed" class="span5" title="Medida del Material" placeholder="Medida" REQUiRED />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="label label-info">Unidad</label>
					<div class="controls">
						<select name="cbound" id="cbound" class="span2">
							<?php
								$cn = new PostgreSQL();
								$query = $cn->consulta("SELECT * FROM admin.unidad");
								if ($cn->num_rows($query) > 0) {
									while ($result = $cn->ExecuteNomQuery($query)) {
										echo "<option value='".$result['uninom']."'>".$result['uninom']."</option>";
									}
								}
								$cn->close($query);
							?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label for="" class="label label-info">Precio</label>
					<div class="controls">
						<input type="number" id="txtprecio" name="txtprecio" class="input-small" title="Precio" step="0.01"  REQUIRED />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="label label-info">Marca</label>
					<div class="controls">
						<input type="text" class="span2" name="txtmar" id="txtmar" title="Ingrese Marca" placeholder="Marca" REQUIRED />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="label label-info">Modelo</label>
					<div class="controls">
						<input type="text" class="span2" name="txtmod" id="txtmod" title="Ingrese Modelo" placeholder="Modelo" REQUIRED />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="label label-info">Acabado</label>
					<div class="controls">
						<input type="text" class="span2" name="txtacab" id="txtacab" title="Ingrese Acabado" placeholder="Acabado" REQUIRED />
					</div>
				</div>
				<div class="control-group">
					<label for="" class="label label-info">Area</label>
					<div class="controls">
						<input type="number" step="0.01"  class="input-small" name="txtarea" id="txtarea" title="Ingrese Area" placeholder="Area" />
					</div>
				</div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
            <button type="Submit" name="btnsave" value="btnsave" class="btn btn-primary">Guardar cambios</button>
        </div>
        </form>
    </div>
</section>
<footer>
</footer>
</body>
</html>
<?php
}else{
	redirect(1);
}
?>