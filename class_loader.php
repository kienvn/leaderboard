<?php

require_once("config/main_config.php");

spl_autoload_register("php2012_autoload");

function php2012_autoload($className) {
    global $configuration;

    // adapt the class name to the file name
    $className = call_user_func($configuration["ADAPT_FUNCTION"], $className);

    // check if we are not in production mode - there can be more folders to the path
    $extraFolder = ($configuration["PRODUCTION"] == false ? DIRECTORY_SEPARATOR . $configuration["FOLDER_AFTER_DOC_ROOT"] : "");
    $path = $_SERVER["DOCUMENT_ROOT"] . $extraFolder . DIRECTORY_SEPARATOR . $configuration["CLASS_FOLDER"] . DIRECTORY_SEPARATOR . $className . "." . $configuration["CLASS_EXTENSION"];
    require_once($path);
}