<?php

	        
	// Define path to application directory
	defined("APPLICATION_PATH") || define("APPLICATION_PATH", realpath(dirname(__FILE__) ));
	
	defined("APPLICATION_ENV") || define("APPLICATION_ENV", (getenv("APPLICATION_ENV") ? getenv("APPLICATION_ENV") : "development"));


	set_include_path(get_include_path() . PATH_SEPARATOR . APPLICATION_PATH. '/library');

	require_once "Zend/Application.php";

	
	$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . "/configs/application.ini");
	//Autoload Namespace
	$loader = Zend_Loader_Autoloader::getInstance();
	$loader->registerNamespace(array('Lazada_', 'Zend_', 'REST_'));

?>