<?php

class Game {

    private $database;
    private $studentFactory;
    
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
}

?>
