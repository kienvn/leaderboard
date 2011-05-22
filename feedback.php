<?php

header("Content-Type: text/html; charset=utf-8");

require_once("classes/Smarty.class.php");
require_once("class_loader.php");
require_once("config/database_config.php");


$smarty = new Smarty();
$feedbackPage = "feedbackPage.tpl";

$fs = FolderStructure::getInstance();

$smarty->setTemplateDir($fs->getTemplatesFolder());
$smarty->assign("imagesFolder", $fs->getImagesFolder());
$smarty->assign("cssFolder", $fs->getCSSFolder());
$smarty->display($feedbackPage);