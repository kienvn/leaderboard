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
        $points = $database->escape($_POST["points"]);
        $studentName = $database->escape($_POST["studentName"], array("htmlspecialchars", "trim"));
        $studentId = $database->escape($_POST["studentId"]);
        $lecture = $database->escape($_POST["lecture"]);
        $type = $database->escape($_POST["type"]);

        $result = array();

        if ($studentId == -1) {
            $studentId = $game->studentFactory->createStudent($studentName);
            $result["studentId"] = $studentId;
        }

        $game->addPoints($studentId, $points, $type, $lecture);
        $result["result"] = "success";
        echo json_encode($result);
        break;
    default:
        break;
}