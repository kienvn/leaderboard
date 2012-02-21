<?php
require_once "includes.php";

$smarty = new Smarty();
$smarty -> setTemplateDir("templates");

$smarty -> display("adminPage.tpl");
