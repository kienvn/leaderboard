<?php

header("Content-Type: text/html; charset=utf-8");
require_once("class_loader.php");
require_once("config/database_config.php");

$game = new Game($database);

$method = $_POST["method"];

switch ($method) {
    case "getAllStudents" :
        $students = $game->studentFactory->getAll();
        echo json_encode($students);
        break;
    case "addPoints" :
        $points = $_POST["points"];
        $name = $_POST["studentName"];
        $lecture = $_POST["lecture"];
        
        break;
    default:
        break;
}