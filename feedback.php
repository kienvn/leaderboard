<?php

session_start();
header("Content-Type: text/html; charset=utf-8");

require_once("classes/Smarty.class.php");
require_once("class_loader.php");
require_once("config/database_config.php");

function feedback_check($arr, $values) {
    foreach ($values as $val) {
        if (!isset($arr[$val]) || empty($arr[$val])) {
            return FALSE;
        }
    }

    return TRUE;
}

$errorMessage = "";
$successMessage = "";

if (isset($_POST["feedbackForm"]) && !empty($_POST["feedbackForm"])) {
    // check captcha
    $captcha = array_shift($_POST["captcha"]);
    if (isset($_SESSION["captcha"]) && $captcha == $_SESSION["captcha"]) {
        if (feedback_check($_POST, array("nameInput", "opinionInput"))) {
            $database = new Database($dbConfig);
            $database->setEncoding("UTF8");
            $name = $_POST["nameInput"];
            $opinion = $_POST["opinionInput"];

            $likedLeaderboard = isset($_POST["likedLeaderboard"]) ? 1 : 0;
            $learnedSomething = isset($_POST["learnedSomething"]) ? 1 : 0;

            $extraEscapingFunctions = array("trim", "htmlspecialchars");

            $name = substr($name, 0, 255);
            $name = $database->escape($name, $xtraEscapingFunctions);
            $opinion = substr($opinion, 0, 500);
            $opinion = $database->escape($opinion, $extraEscapingFunctions);
            $timeStamp = time();

            $sql = "INSERT INTO feedback(name, opinion, likedLeaderboard, learnedSomething, timestamp) VALUES('%s', '%s', %d, %d, %d)";
            $sql = sprintf($sql, $name, $opinion, $likedLeaderboard, $learnedSomething, $timeStamp);
            $database->query($sql);
            $successMessage = "Благодаря ти $name. Мнението ти се съхранява на топло и сигурно място.";
        } else {
            $errorMessage = "Нещо не е въведено :)";
        }
    } else {
        $errorMessage = "Картинката не съвпадата :(";
    }
}


$smarty = new Smarty();
$fs = FolderStructure::getInstance();
$captcha = new VisualCaptcha();

$feedbackPage = "feedbackPage.tpl";
$imagesFolder = $fs->getImagesFolder();


$captcha->setImagesFolder($imagesFolder . DIRECTORY_SEPARATOR . "captcha");
$captcha->setCorrectImageNames(array("ivaylo.png", "rado.jpg", "emo.jpg", "nikolay.jpg", "joro.jpg"));

// generate 4 random images where one is correct
$files = $captcha->generate(4);

unset($_SESSION["captcha"]);

$radioButtonValues = array();
$seedForRandomToken = "abcdefghijklmnopqrstuvw0123456789";
$correct = $captcha->getLastCorrect();

foreach ($files as $image) {
    $token = new Token($seedForRandomToken, 10/* 10 chars long */);
    $randomValue = md5($token->getString());

    if ($image === $correct) {
        $_SESSION["captcha"] = $randomValue;
    }
    $radioButtonValues[$randomValue] = $captcha->getImagesFolder() . DIRECTORY_SEPARATOR . $image;
}


$smarty->setTemplateDir($fs->getTemplatesFolder());
$smarty->assign("imagesFolder", $imagesFolder);
$smarty->assign("cssFolder", $fs->getCSSFolder());
$smarty->assign("captchaImages", $radioButtonValues);
$smarty->assign("errorMessage", $errorMessage);
$smarty->assign("successMessage", $successMessage);
$smarty->display($feedbackPage);