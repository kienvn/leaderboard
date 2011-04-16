<?php

header("Content-Type: text/html; charset=utf-8");

require_once("classes/Smarty.class.php");
require_once("class_loader.php");
require_once("config/database_config.php");


// this object can be accessed in methods or functions by calling global $database;
$database = new Database($dbConfig);
$database->setEncoding("UTF8");


$game = new Game($database);
$students = array();

if (isset($_GET["lecture"]) && !empty($_GET["lecture"])) {

    $getLecture = (int) $_GET["lecture"];
    $students = $game->leaderboard("lecture", $getLecture);
} else if (isset($_GET["homework"]) && !empty($_GET["homework"])) {
    $getHomework = (int) $_GET["homework"];
    $students = $game->leaderboard("homework", $getHomework);
} else {
    $students = $game->leaderboard();
}

// calculate the total sum
$totalScore = 0.0;
foreach ($students as $student) {
    $totalScore += $student->score;
}

$smarty = new Smarty();
$smarty->setTemplateDir("templates");
$smarty->assign("totalScore", $totalScore);
$smarty->assign("students", $students);
$smarty->display("index.tpl");
?>