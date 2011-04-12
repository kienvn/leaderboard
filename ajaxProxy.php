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
        $studentName = $_POST["studentName"];
        $studentId = $_POST["studentId"];
        $lecture = $_POST["lecture"];
        $type = $_POST["type"];

        $game->addPoints($studentId, $points, $type, $lecture);
        echo json_encode(array("result" => "success"));
        break;
    default:
        break;
}