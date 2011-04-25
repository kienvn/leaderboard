<?php
require_once("classes/Smarty.class.php");
require_once("class_loader.php");
require_once("config/database_config.php");


$smarty = new Smarty();
$smarty->setTemplateDir("templates");

$smarty->display("adminPage.tpl");