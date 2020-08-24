<?php

require_once 'core/vendor/autoload.php';

use core\Router;

session_start();

spl_autoload_register( function ( $class ) {
	$path = str_replace( '\\', '/', $class . '.php' );
	if ( file_exists( $path ) ) {
		require $path;
	}
} );

$router = new Router();

$router->setRequestUri($_SERVER['REQUEST_URI']);

$router->setBase( require 'url_base.php');

$router->run();
