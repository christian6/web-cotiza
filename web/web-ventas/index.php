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
	<title>Bienvenido a Ventas</title>
	<link rel="shortcut icon" href="../ico/icrperu.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style-home.css">
  	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js"></script>
    <script src="../modules/jquery1.9.js"></script>
    <script src="../modules/jquery-ui.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
    <script src="js/home.js"></script>
</head>
<body>
	<?php include ("includes/menu-ventas.inc"); ?>
	<div class="view">
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3 well">
          <div class="nav-header">Estados</div>
            <div class="msg">
              <div class="row-fuid">
                <?php
                  $cn = new PostgreSQL();
                  $query = $cn->consulta("SELECT COUNT(*) FROM almacen.pedido WHERE esid LIKE '35'");
                  $r = $cn->ExecuteNomQuery($query);
                  $cn->close($query);
                ?>
                <label for="label" class="label label-inverse">Pedidos Aprobados <span class="badge pull-right"><?php echo $r[0]; ?></span></label>
                <?php
                  $cn = new PostgreSQL();
                  $query = $cn->consulta("SELECT COUNT(*) FROM almacen.pedido WHERE esid LIKE '38'");
                  $r = $cn->ExecuteNomQuery($query);
                  $cn->close($query);
                ?>
                <label for="label" class="label label-inverse">Suministros Aprobados <span class="badge pull-right"><?php echo $r[0]; ?></span></label>
              </div>  
            </div>
        </div>
        <div class="span9 well">
          <div class="nav-header">
            <i class="icon-inbox"></i> mensajeria
            <?php
              $cn = new PostgreSQL();
              $query = $cn->consulta("SELECT COUNT(*) FROM admin.mensaje WHERE empdni LIKE '".$_SESSION['dni-icr']."' AND esid NOT LIKE '57'");
              $r = $cn->ExecuteNomQuery($query);
              $cn->close($query);
              echo "&nbsp;&nbsp;&nbsp; TOTAL <span class='badge badge-info'>".$r[0]."</span>";

              $cn = new PostgreSQL();
              $query = $cn->consulta("SELECT COUNT(*) FROM admin.mensaje WHERE empdni LIKE '".$_SESSION['dni-icr']."' AND esid LIKE '56'");
              $r = $cn->ExecuteNomQuery($query);
              $cn->close($query);
              echo "&nbsp;&nbsp;&nbsp;NO LEIDOS <span class='badge badge-info'>".$r[0]."</span>";

              $cn = new PostgreSQL();
              $query = $cn->consulta("SELECT COUNT(*) FROM admin.mensaje WHERE empdni LIKE '".$_SESSION['dni-icr']."' AND esid LIKE '55'");
              $r = $cn->ExecuteNomQuery($query);
              $cn->close($query);
              echo "&nbsp;&nbsp;&nbsp; LEIDOS <span class='badge badge-info'>".$r[0]."</span>";
            ?>
          </div>
          <div class="msg">
            <div id="message">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="send" class="modal fade in hide">
    <a class="close" data-dismiss="modal">×</a>
    <div class="modal-body">
      <span id="m" class="m">
        <h4>Enviando Mensaje</h4>
      </span>
      <div class="container-fluid">
        <div class="control-group">
          <label for="label"><strong>Enviar a:</strong></label>
          <div class="controls">
            <select class="span3" name="cboemp" id="cboemp">
              <?php
                $cn = new PostgreSQL();
                $query = $cn->consulta("SELECT empdni,empnom,empape FROM admin.empleados");
                if ($cn->num_rows($query) > 0) {
                  while ($result = $cn->ExecuteNomQuery($query)) {
                    echo "<option value='".$result['empdni']."'>".$result['empnom']." ".$result['empape']."</option>";
                  }
                }
                $cn->close($query);
              ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label for="label">Asunto:</label>
          <div class="controls">
            <input type="text" name="txtasunto" id="txtasunto" placeholder="Asunto" title="Ingrese Asunto a tratar" class="span4">
          </div>
        </div>
        <div class="control-group">
          <div class="controls">
            <textarea name="txtbody" id="txtbody" cols="5" rows="10"></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button type="Button" onClick="send_display(false);" class="btn">Cancelar</button>
      <button type="Button" onClick="send();" class="btn btn-primary">Enviar</button>
    </div>
  </div>
  <footer>
    <nav class="men">
      <div class="navbar">
        <div class="navbar-inner">
          <ul class="nav pull-right">
            <li class="divider-vertical"></li>
            <li><a href="javascript:send_display(true);"><i class="icon-envelope"></i>  <strong>Redactar</strong></a></li>
            <li class="divider-vertical"></li>
            <li><a href="javascript:refresh_mail();"><i class="icon-refresh"></i> <b>Actualizar</b></a></li>
          </ul>
        </div>
      </div>
    </nav>
  </footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>