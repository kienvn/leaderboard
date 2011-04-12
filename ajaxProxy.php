<?php

header("Content-Type: text/html; charset=utf-8");
require_once("class_loader.php");
require_once("config/database_config.php");

$method = $_POST["method"];

switch ($method) {
    case "getAllStudents":
        $factory = new StudentFactory($database);
        $students = $factory->getAll();
        echo json_encode($students);
        break;
    case "addPoints" :
        break;
    default:
        break;
}