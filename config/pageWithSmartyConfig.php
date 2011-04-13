<?php

require_once("class_loader.php");

$classesToLoad = array("config", "database", "game", "abstractfactory", "studentfactory", "student");

foreach ($classesToLoad as $className) {
    $path = $configuration["CLASS_FOLDER"] . DIRECTORY_SEPARATOR . $className . "." . $configuration["CLASS_EXTENSION"];
    require_once($path);
}