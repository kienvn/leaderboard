<?php

require_once("classes/Smarty.class.php");
require_once("class_loader.php");
require_once("config/database_config.php");

$database = new Database($dbConfig);
$game = new Game($database);

// init Smarty
$smarty = new Smarty();
$smarty->setTemplateDir("templates");

if (isset($_GET["pid"]) && !empty($_GET["pid"])) {
    $pid = $_GET["pid"];
    
    // sanitize the input
    $pid = $database->escape($pid);
    $history = $game->getHistoryForStudent($pid);
    var_dump($history);
} else {
    $error = "No player selected";
    $smarty->assign("error", $error);
    $smarty->display("playerErrorPage.tpl");
}