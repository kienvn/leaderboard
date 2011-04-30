<?php
header("Content-Type: text/html; charset=utf-8");

require_once("classes/Smarty.class.php");
require_once("class_loader.php");
require_once("config/database_config.php");

$database = new Database($dbConfig);
$database->setEncoding("UTF8");

$game = new Game($database);

// init Smarty
$smarty = new Smarty();
$smarty->setTemplateDir("templates");

$page = "";

if (isset($_GET["pid"]) && !empty($_GET["pid"])) {
    $pid = $_GET["pid"];
    // sanitize the input
    $pid = $database->escape($pid);

    $student = $game->studentFactory->getById($pid);

    $history = $game->getHistoryForStudent($pid);
    
    $smarty->assign("playerName", $student->name);
    $smarty->assign("history", $history);
    $page = "playerPage.tpl";
} else {
    $error = "No player selected";
    $smarty->assign("error", $error);
    $page = "playerErrorPage.tpl";
}

$smarty->display($page);