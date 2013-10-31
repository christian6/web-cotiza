<div class="navbar navbar-fixed-top navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
      <a href="http://190.41.246.91/web/index.php" class="brand"><img class="icr-img" src="http://190.41.246.91/web/resource/icrlogo.png"></a>
			<a class="brand" href="http://190.41.246.91/web/web-almacen/home.php">Modulo Almacen</a>
			<div class="nav-collapse in">
				<ul class="nav" role="navigation">
					<li class="active"><a href="http://190.41.246.91/web/web-almacen/home.php"><i class="icon-home"></i></a></li>
          <!--<li class="dropdown">
             <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pedidos <b class="caret"></b></a>
             <ul class="dropdown-menu">
               <li><a href="http://190.41.246.91/web/web-almacen/pedidosal.php">Pedidos</a></li>
               <li><a href="http://190.41.246.91/web/web-almacen/estadopedido.php">Consulta Pedido</a></li>
             </ul>
          </li>-->
					<!--<li class="divider-vertical"></li>
					<li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Suministro <b class="caret"></b></a>
		          <ul class="dropdown-menu">
		            <li><a href="http://190.41.246.91/web/web-almacen/aprobar-suministro.php"><i class="icon-lock"></i> Aprobar Suministro</a></li>
                <li class="divider"></li>
		            <li><a href="http://190.41.246.91/web/web-almacen/osumcot.php">Osum a SCotizacion</a></li>
		          </ul>
		      </li>-->
          <!--<li class="divider-vertical"></li>-->
          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Existencia <b class="caret"></b></a>
              <ul class="dropdown-menu">
              <li><a href="http://190.41.246.91/web/web-almacen/existencia-all.php">Por Almacen</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/existencia-all-pedido.php">Total de Pedidos</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/stock-fisico.php">Stock Fisico</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/existencia_total.php">Pedidos y Proyectos</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/suministro-pro.php">Proyectos</a></li>
              <li class="divider"></li>
              <li class="nav-header">Consultas</li>
              <li><a href="http://190.41.246.91/web/web-almacen/ini-kardex.php">Kardex</a></li>
            </ul>
          </li>
          <li class="divider-vertical"></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Salidas <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="http://190.41.246.91/web/web-almacen/aprobarpedido.php"><i class="icon-lock"></i> Aprobar Pedido</a></li>
              <li class="divider"></li>
              <li><a href="http://190.41.246.91/web/web-almacen/verpedido.php">Atender Pedido</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/pedidoxgenerar.php">Generar Documento Salida</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/verdocsalida.php">Pedidos Atendidos</a></li>
            </ul>
          </li>
          <li class="divider-vertical"></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ingreso <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li class="nav-header">INGRESOS INICIALES</li>
              <li><a href="http://190.41.246.91/web/web-almacen/ingresoinventariomat.php">Ingreso Material Inicial</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/ingresoinicial.php">Periodo pasado con Stock </a></li>
              <li class="diveder"></li>
              <li class="nav-header">Ingresos</li>
              <li><a href="http://190.41.246.91/web/web-almacen/ingresoxcompra.php">Ingreso Orden de Compra</a></li>
              <li><a href="notaingreso.php">Nota de Ingreso</a></li>
              <li class="divider"></li>
              <li class="nav-header">Devoculiones</li>
              <li><a href="http://190.41.246.91/web/web-almacen/devolucion.php">Crear Devolucion</a></li>
              <li><a href="http://190.41.246.91/web/web-almacen/list-devolucion.php">Devoluciones</a></li>
            </ul>
          </li>
				</ul>
        <ul class="nav pull-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php echo $_SESSION['nom-icr']; ?>
              <i class="icon-user icon-white"></i>
              <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
              <li><label style="font-size: .8em; font-weight: bold; padding-left: .8em; margin: 0em;" for="label">Cargo:&nbsp;<?php echo $_SESSION['car-icr']; ?></label></li>
              <li><label style="font-size: .8em; font-weight: bold; padding-left: .8em; margin: 0em;"  for="label">Usuario:&nbsp;<?php echo $_SESSION['user-icr']; ?></label></li>
              <li><label style="font-size: .8em; font-weight: bold; padding-left: .8em; margin: 0em;"  for="label">Dni:&nbsp;<?php echo $_SESSION['dni-icr']; ?></label></li>
              <li class="divider"></li>
              <li><a href="javascript:location.href='http://190.41.246.91/web/includes/session-destroy.php';"><i class="icon-off"></i> Cerrar Session</a></li>
            </ul>
          </li>
        </ul>
			</div>
		</div>
	</div>
</div>