<?php
header("Content-Type: text/html; charset=utf-8");
require_once "includes.php";

$game = new Game($database);
$auth = new Authentication($database);

$smarty = new Smarty();
$smarty -> setTemplateDir("templates");

$loginTpl = "loginForm.tpl";

if (isset($_GET["logout"])) {
	$auth -> logout();
	Navigation::go(Navigation::$INDEX_PAGE);
}

if ($auth -> checkLogin()) {
	Navigation::go(Navigation::$ADMIN_PAGE);
}

$errorText = "";

if (isset($_POST["loginForm"])) {

	$user = $database -> escape($_POST["username"]);
	$password = $database -> escape($_POST["password"]);
	$userId = $game -> studentFactory -> getIdByName($user);

	$res = $auth -> login($userId, $password);
	if ($res == FALSE) {
		$errorText = "Wrong credentials";
	} else {
		Navigation::go(Navigation::$ADMIN_PAGE);
	}
}

$smarty -> assign("errorText", $errorText);
$smarty -> display($loginTpl);
