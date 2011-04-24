<?php

header("Content-Type: text/html; charset=utf-8");

require_once("classes/Smarty.class.php");
require_once("class_loader.php");
require_once("config/database_config.php");

$database = new Database($dbConfig);
$database->setEncoding("UTF8");
$game = new Game($database);
$auth = new Authentication($database);

$smarty = new Smarty();
$smarty->setTemplateDir("templates");

if (isset($_GET["logout"])) {
    $auth->logout();
    Navigation::go("index.php");
}

$errorText = "";

if (isset($_POST["loginForm"])) {

    $user = $database->escape($_POST["username"]);
    $password = $database->escape($_POST["password"]);
    $userId = $game->studentFactory->getIdByName($user);

    $res = $auth->login($userId, $password);
    if ($res == FALSE) {
        $errorText = "Wrong credentials";
    } else {
        Navigation::go("adminPage.html?token=" . $res);
    }
}

$smarty->assign("errorText", $errorText);
$smarty->display("loginForm.tpl");
