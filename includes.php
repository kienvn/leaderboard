<?php
require_once "classes/Smarty.class.php";
require_once "class_loader.php";
require_once "config/db_config.php";

// this object can be accessed in methods or functions by calling global $database;
$database = new Database($dbConfig);
$database -> setEncoding("UTF8");
