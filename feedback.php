<?php
session_start();
header("Content-Type: text/html; charset=utf-8");

require_once("classes/Smarty.class.php");
require_once("class_loader.php");
require_once("config/database_config.php");


$smarty = new Smarty();
$feedbackPage = "feedbackPage.tpl";

$fs = FolderStructure::getInstance();

$captcha = new VisualCaptcha();
$captcha->setImagesFolder($fs->getImagesFolder() . DIRECTORY_SEPARATOR . "captcha");
$captcha->setCorrectImageNames(array("ivaylo.png", "rado.jpg", "emo.jpg", "nikolay.jpg"));

$files = $captcha->generate(4);
unset($_SESSION["captcha"]);

$radioButtonValues = array();
$seedForRandomToken = "abcdefghijklmnopqrstuvw0123456789";
$correct = $captcha->getLastCorrect();

foreach($files as $image) {
    $token = new Token($seedForRandomToken, 10);
    $randomValue = md5($token->getString());
    
    if($image === $correct) {
        $_SESSION["captcha"] = $randomValue;
    }
    $radioButtonValues[$randomValue] = $captcha->getImagesFolder() . DIRECTORY_SEPARATOR . $image;
}


$smarty->setTemplateDir($fs->getTemplatesFolder());
$smarty->assign("imagesFolder", $fs->getImagesFolder());
$smarty->assign("cssFolder", $fs->getCSSFolder());
$smarty->assign("captchaImages", $radioButtonValues);
$smarty->display($feedbackPage);