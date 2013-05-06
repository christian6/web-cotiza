<div class="navbar navbar-fixed-top navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
      <a class="brand"><img class="icr-img" src="http://190.41.246.91/web/resource/icrlogo.png"></a>
			<a class="brand" href="#">Modulo Almacen</a>
			<div class="nav-collapse in">
				<ul class="nav" role="navigation">
					<li class="active"><a href="http://190.41.246.91/web/web-almacen/home.php"><i class="icon-home"></i></a></li>
          <li class="dropdown">
             <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pedidos <b class="caret"></b></a>
             <ul class="dropdown-menu">
               <li><a href="http://190.41.246.91/web/web-almacen/pedidosal.php">Pedidos</a></li>
               <li><a href="http://190.41.246.91/web/web-almacen/estadopedido.php">Consulta Pedido</a></li>
             </ul>
          </li>
					<li class="divider-vertical"></li>
					<li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Suministro <b class="caret"></b></a>
		          <ul class="dropdown-menu">
		            <li><a href="http://190.41.246.91/web/web-almacen/aprobar-suministro.php">Aprobar Suministro</a></li>
		            <li><a href="http://190.41.246.91/web/web-almacen/osumcot.php">Osum a SCotizacion</a></li>
		          </ul>
		      </li>
          <li class="divider-vertical"></li>
          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Existencia <b class="caret"></b></a>
              <ul class="dropdown-menu">
              <li><a href="http://190.41.246.91/web/web-almacen/existencia-all.php">Por Almacen</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/existencia-all-pedido.php">Total de Pedidos</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/stock-fisico.php">Stock Fisico</a></li>
            </ul>
          </li>
          <li class="divider-vertical"></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Salidas <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="http://190.41.246.91/web/web-almacen/verpedido.php">Atender Pedido</a></li>
              <li class="divider"></li>
              <li><a href="http://190.41.246.91/web/web-almacen/aprobarpedido.php">Aprobor Pedido</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/pedidoxgenerar.php">Generar Documento Salida</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/verdocsalida.php">Pedidos Atendidos</a></li>
            </ul>
          </li>
          <li class="divider-vertical"></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ingreso <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="http://190.41.246.91/web/web-almacen/ingresoinventariomat.php">Ingreso Material Inicial</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/ingresoxcompra.php">Ingreso Orden de Compra</a></li>
              <li class="divider"></li>
            </ul>
          </li>
				</ul>
        <ul class="nav pull-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php echo $_SESSION['nom-icr']; ?>
              <i class="icon-cog icon-white"></i>
              <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
              <li class="icr-drop"><b>&nbsp;Cargo:&nbsp;<?php echo $_SESSION['car-icr']; ?></b></li>
              <li class="icr-drop"><b>&nbsp;Usuario:&nbsp;<?php echo $_SESSION['user-icr']; ?></b></li>
              <li class="icr-drop"><b>&nbsp;Dni:&nbsp;<?php echo $_SESSION['dni-icr']; ?></b></li>
              <li class="divider"></li>
              <li><a href="javascript:location.href='http://190.41.246.91/web/includes/session-destroy.php';">Cerrar Session</a></li>
            </ul>
          </li>
        </ul>
			</div>
		</div>
	</div>
</div>