<?php

	/**
	 * Configuration for auto-loading classes
	 * CLASS_FOLDER points to the folder relative to the root directory!!
	 * CLASS_EXTENSION is the file extension of the PHP file where the class resides		
	 */
	$configuration["CLASS_FOLDER"] = "classes";
	$configuration["CLASS_EXTENSION"] = "php";
	

	/**
	 * Turn PRODUCTION to true, if uploaded to a web-host
	 * The FOLDER_AFTER_DOC_ROOT parameter is the path, after the document root, where the code resides
	 * ADAPT_FUNCTION points to the function name, that is mapped to every class name
	 * for example class Database has file database.php
	 * If other behaviour is desired, write a function and change the ADAPT_FUNCTION parameter value
	 */
	$configuration["PRODUCTION"] = false;
	$configuration["FOLDER_AFTER_DOC_ROOT"] = "leaderboard";
	$configuration["ADAPT_FUNCTION"] = "leaderboard_adaptClassName";

	function leaderboard_adaptClassName($className) {
		$className = strtolower($className);
		return $className; 
	}
?>
