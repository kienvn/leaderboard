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
            case "homework":
                return $this->leaderboardStudentsByHomework($filterValue);
                break;

            default:
                return $this->leaderboardStudents();
                break;
        }
    }

    public function addPoints($studentId, $points, $type, $lecture) {
        $sql = "INSERT INTO leaderboard(type, lecture, student_id, points)
                VALUES('%s', %d, %d, %f)";
        $sql = sprintf($sql, $type, $lecture, $studentId, $lecture);
        $this->database->query($sql);
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

    private function leaderboardStudentsByHomework($homework) {
        $sql = "SELECT student_id, SUM(points) as SCORE
                FROM leaderboard
                WHERE lecture = %d AND type = 'homework'
                GROUP BY student_id
                ORDER BY SCORE DESC";
        
        $sql = sprintf($sql, $homework);
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
