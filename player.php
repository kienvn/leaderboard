<?php

header("Content-Type: text/html; charset=utf-8");

require_once("classes/Smarty.class.php");
require_once("class_loader.php");
require_once("config/db_config.php");

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
    ksort($history);

    // calculate the total score
    $score = 0.0;
    $totalQuestions = 0;
    $totalAnswers = 0;
    $totalHomeworks = 0;

    foreach ($history as $lectureNumber => $arrayOfHistoryObjects) {
        foreach ($arrayOfHistoryObjects as $historyObj) {
            $score += $historyObj->points;
            switch ($historyObj->type) {
                case "question":
                    $totalQuestions++;
                    break;
                case "answer" :
                    $totalAnswers++;
                    break;
                case "homework" :
                    $totalHomeworks++;
                    break;
                default:
                    break;
            }
        }
    }

    $smarty->assign("playerName", $student->name);
    $smarty->assign("history", $history);
    $smarty->assign("totalScore", $score);
    $smarty->assign("totalQuestions", $totalQuestions);
    $smarty->assign("totalAnswers", $totalAnswers);
    $smarty->assign("totalHomeworks", $totalHomeworks);


    $page = "playerPage.tpl";
} else {
    $error = "No player selected";
    $smarty->assign("error", $error);
    $page = "playerErrorPage.tpl";
}

$smarty->display($page);