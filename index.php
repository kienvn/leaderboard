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

function getFilterType() {
    if (isset($_GET["lecture"]) && !empty($_GET["lecture"])) {
        return "lecture";
    }

    if (isset($_GET["homework"]) && !empty($_GET["homework"])) {
        return "homework";
    }

    return "default";
}

switch (getFilterType ()) {
    case "lecture":
        $getLecture = (int) $_GET["lecture"];
        $students = $game->leaderboard("lecture", $getLecture);
        break;
    case "homework" :
        $getHomework = (int) $_GET["homework"];
        $students = $game->leaderboard("homework", $getHomework);
        break;
    case "default" :
        $students = $game->leaderboard();
        break;
    default:
        $students = $game->leaderboard();
        break;
}

$lectures = $game->getLectures();

// calculate the total sum
$totalScore = 0.0;
foreach ($students as $student) {
    $totalScore += $student->score;
}

$smarty = new Smarty();
$smarty->setTemplateDir("templates");
$smarty->assign("totalScore", $totalScore);
$smarty->assign("students", $students);
$smarty->assign("lectures", $lectures);
$smarty->display("index.tpl");