<div class="navbar navbar-fixed-top navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a href="http://190.41.246.91/web/index.php" class="brand"><img class="icr-img" src="http://190.41.246.91/web/resource/icrlogo.png"></a>
			<a class="brand" href="http://190.41.246.91/web/web-manager/index.php">Manager</a>
			<div class="nav-collapse in">
				<ul class="nav" role="navigation">
					<li class="active">
						<a href="http://190.41.246.91/web/web-manager/index.php"><i class="icon-home"></i>
						</a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Proyectos <b class="caret"></b></a>
			            <ul class="dropdown-menu">
			               <li><a href="http://190.41.246.91/web/web-manager/proyectoma.php">Proyectos</a></li>
			               <li><a href="http://190.41.246.91/web/web-manager/manager-pro.php">Manager</a></li>
			            </ul>
			        </li>
			        <li class="divider-vertical"></li>
			        <li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Manteniminetos <b class="caret"></b></a>
			            <ul class="dropdown-menu">
			               <li><a href="http://190.41.246.91/web/web-mantenice/materiales.php">Materiales</a></li>
			               <li><a href="http://190.41.246.91/web/web-manager/solicitudmat.php">Solicitud de Materiales</a></li>
			            </ul>
			        </li>
			        <li class="divider-vertical"></li>
			        <li class="dropdown">
			        	<a href="#" class="dropdown-toogle" data-toggle="dropdown">Analisís <b class="caret"></b></a>
			        	<ul class="dropdown-menu">
			        		<li><a href="http://190.41.246.91/web/web-manager/proxanio.php">Proyecto</a></li>
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
		              <li><label style="font-size: .8em; font-weight: bold; padding-left: .8em; margin: 0em;" for="label">Usuario:&nbsp;<?php echo $_SESSION['user-icr']; ?></label></li>
		              <li><label style="font-size: .8em; font-weight: bold; padding-left: .8em; margin: 0em;" for="label">Dni:&nbsp;<?php echo $_SESSION['dni-icr']; ?></label></li>
		              <li class="divider"></li>
		              <li><a href="javascript:location.href='http://190.41.246.91/web/includes/session-destroy.php';"><i class="icon-off"></i> Cerrar Session</a></li>
		        </ul>
          </li>
        </ul>
			</div>
		</div>
	</div>
</div>