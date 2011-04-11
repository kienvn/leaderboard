<?php

class Game {

    private $database;
    private $studentFactory;
    private $queries = array(
        "questions" => "SELECT name, COUNT(type) AS questions FROM leaderboard WHERE type='question'%s GROUP BY name",
        "answers" => "SELECT name, COUNT(type) AS answers FROM leaderboard WHERE type='answer'%s GROUP BY name",
        "homework" => "SELECT name, COUNT(type) as homework FROM leaderboard WHERE type='homework'%s GROUP BY name",
        "names" => "SELECT DISTINCT name FROM leaderboard",
        "namesByLecture" => "SELECT DISTINCT name FROM leaderboard WHERE lecture =%d"
    );
    private $coefs = array("questions" => 0.5, "answers" => 1, "homework" => 5);

    public function __construct($database) {
        $this->database = $database;
        $this->studentFactory = new StudentFactory($database);
    }

    public function leaderboard($filterBy = "", $filterValue = "") {
        switch ($filterBy) {
            case "lecture":
                return $this->leaderboardStudentsByLecture($filterValue);
                break;

            default:
                return $this->leaderboardStudents();
                break;
        }
    }

    private function leaderboardStudents() {
        $sql = "SELECT student_id, SUM(points) as SCORE
                FROM leaderboard
                GROUP BY student_id
                ORDER BY SCORE DESC";

        $res = $this->database->query($sql);
        return $this->createStudentsFromResult($res);
    }

    private function leaderboardStudentsByLecture($lecture) {
        $sql = "SELECT student_id, SUM(points) as SCORE
                FROM leaderboard
                WHERE lecture = %d AND type != 'homework'
                GROUP BY student_id
                ORDER BY SCORE DESC";

        $sql = sprintf($sql, $lecture);
        $res = $this->database->query($sql);
        return $this->createStudentsFromResult($res);
    }

    private function createStudentsFromResult($res) {
        $students = array();
        while (($row = $this->database->fetch_array($res)) !== FALSE) {
            $currentStudent = $this->studentFactory->getById($row["student_id"]);
            $currentStudent->setScore($row["SCORE"]);
            $students[] = $currentStudent;
        }

        return $students;
    }

    public function getStudents() {
        $sql = $this->queries["names"];
        $names = $this->getNames($sql);

        $whereClause = "";
        $sqlForAnswers = sprintf($this->queries["answers"], $whereClause);
        $sqlForQuestions = sprintf($this->queries["questions"], $whereClause);
        $sqlForHomework = sprintf($this->queries["homework"], $whereClause);

        $this->setStudentsScore($names, $sqlForAnswers, $sqlForQuestions, $sqlForHomework);
        $students = array();

        while (list($key, $value) = each($names)) {
            $s = new Student();
            $s->name = $key;
            $s->score = $value;
            $students[] = $s;
        }
        return $students;
    }

    public function getStudentsByLecture($lecture) {
        $lecture = (int) $lecture;
        $lecture = $this->database->escape($lecture);

        $sql = sprintf($this->queries["namesByLecture"], $lecture);
        $names = $this->getNames($sql);

        $whereClause = " AND lecture = " . $lecture;

        $sqlForAnswers = sprintf($this->queries["answers"], $whereClause);
        $sqlForQuestions = sprintf($this->queries["questions"], $whereClause);

        $this->setStudentsScore($names, $sqlForAnswers, $sqlForQuestions);
        $students = array();

        while (list($key, $value) = each($names)) {
            $s = new Student();
            $s->name = $key;
            $s->score = $value;
            $students[] = $s;
        }
        return $students;
    }

    private function setStudentsScore(&$studentsAssoc, $ansSql, $qstSql, $homeworkSql = "") {
        $answersRes = $this->database->query($ansSql);
        while (($row = $this->database->fetch_array($answersRes)) != false) {
            $studentsAssoc[$row["name"]] += (int) $row["answers"] * $this->coefs["answers"];
        }


        $questionsRes = $this->database->query($qstSql);
        while (($row = $this->database->fetch_array($questionsRes)) != false) {
            $studentsAssoc[$row["name"]] += (int) $row["questions"] * $this->coefs["questions"];
        }

        if ($homeworkSql != "") {
            $homeworkRes = $this->database->query($homeworkSql);
            while (($row = $this->database->fetch_array($homeworkRes)) != false) {
                $studentsAssoc[$row["name"]] += (int) $row["homework"] * $this->coefs["homework"];
            }
        }
    }

    private function getNames($sql) {
        $res = $this->database->query($sql);

        $assocStudents = array();

        while (($row = $this->database->fetch_array($res)) != false) {
            $assocStudents[$row["name"]] = 0;
        }

        return $assocStudents;
    }

}

?>
