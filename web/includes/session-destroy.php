<?php
	session_start();

	session_unset();

	session_destroy();

	Header('Location: http://190.41.246.91/web/');

 ?>