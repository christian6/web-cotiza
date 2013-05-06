<?php
include ("../includes/session-trust.php");

if (sesaccess() == 'ok') {
  if (sestrust('k') == 0) {
    redirect();
  }

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Inicio</title>
  	<link rel="stylesheet" href="css/style-home.css">
  	<script src="../modules/jquery1.9.js"></script>
  	<script src="../modules/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
    <script src="../bootstrap/js/bootstrap.js"></script>
  	<script>
  		$(function() {
  		  $("#tabs").tabs();
        $(".dropdown-toggle").dropdown();
  		});
  	</script>
</head>
<body>
<header>
	<hgroup>
		<h1>ICR PERÚ S.A.</h1>
	</hgroup>
</header>
<div class="navbar navbar-inverse" style="width:6em; right:0em; position: absolute;">
  <div class="navbar-inner">
    <div class="container">
      <div class="nav-collapse">
        <ul class="nav pull-right">
          <li class="dropdown">
            <a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cog icon-white"></i></a>
            <ul class="dropdown-menu">
              <li><a href="#"><b>Nombre:</b><?php echo $_SESSION['nom-icr']?></a></li>
              <li><a href="#"><b>Usuario:</b><?php echo $_SESSION['user-icr']?></a></li>
              <li class="divider"></li>
              <li><a href="../includes/session-destroy.php"><i class="icon-lock"></i><b>Cerrar Session</b></a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<section>
	<div id="tabs">
		<nav>
		<ul>
			<li id="t1"><a href="#tabs-1">Pedido al Almacen</a > </li>
			<li id="t2"><a href="#tabs-2">Suministro</a> </li>
			<li id="t3"><a href="#tabs-3">Existencia</a> </li>
			<li id="t4"><a href="#tabs-4">Salida de Almacen</a> </li>
			<li id="t5"><a href="#tabs-5">Ingreso al Almacen</a> </li>
		</ul>
		</nav>
		<div id="tabs-1">
    			<ul>
    			<li><a href="pedidosal.php"><img src="../resource/lista32.png">&nbsp;Pedido Materiales</a></li>
    			</ul>
  		</div>
  		<div id="tabs-2">
    			<ul>
    			<li><a href="aprobarpedido.php"><img src="../resource/check32.png">&nbsp;Aprobar Pedido</a></li>
    			<li><a href="estadopedido.php"><img src="../resource/view32.png">&nbsp;Ver Estado de Pedido</a></li>
    			</ul>
  		</div>
  		<div id="tabs-3">
    			<ul>
    			<li><a href=""><img src="../resource/compra32.png">&nbsp;Consultar Existencia por Almacen</a></li>
    			<li><a href=""><img src="../resource/cajas.gif">&nbsp;Consultar Existencia todos  los Pedidos</a></li>
    			</ul>
  		</div>
  		<div id="tabs-4">
  				<ul>
  				<li><a href=""><img src="../resource/list32.png">&nbsp;Aprobar Pedido de Almacen</a></li>
    			<li><a href=""><img src="../resource/ok.png">&nbsp;Pedidos Aprobados</a></li>
    			<li><a href=""><img src="../resource/camion32.png">&nbsp;Pedidos Atendidos</a></li>
    			</ul>
  		</div>
  		<div id="tabs-5">
  			   
  		</div>
	<script>
		$("#t1").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(143,200,0,1)");
  			},function(){
  				$("#tabs").css("background-color","rgba(143,200,0,0)");
  		});
  		$("#t2").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(53,106,160,1)");
  			},function(){
  				$("#tabs").css("background-color","rgba(53,106,160,0)");
  		});
  		$("#t3").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(255,255,136,1)");
  			},function(){
  				$("#tabs").css("background-color","rgba(255,255,136,0)");
  		});
  		$("#t4").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(247,150,33,1)");
  			},function(){
  				$("#tabs").css("background-color","rgba(53,106,160,0)");
  		});
  		$("#t5").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(229,230,150,1)");
  			},function(){
  				$("#tabs").css("background-color","rgba(53,106,160,0)");
  		});
  		$("#tabs-1").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(143,200,0,1)");
  			},function(){}
  		);
  		$("#tabs-2").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(53,106,160,1)");
  			},function(){}
  		);
  		$("#tabs-3").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(255,255,136,1)");
  			},function(){}
  		);
  		$("#tabs-4").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(247,150,33,1)");
  			},function(){}
  		);
  		$("#tabs-5").hover(
  			function(){
  				$("#tabs").css("background-color","rgba(229,230,150,1)");
  			},function(){}
  		);
	</script>
	</div>
</section>
<footer>
</footer>
</body>
</html>
<?php
}else{
  redirect();
}
?>