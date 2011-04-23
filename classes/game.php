<?php

/**
 * @author RadoRado (a.k.a Rado)
 * The main class that is responsible for wrapping the game logic
 * Needs a Database class to work correctly
 */
class Game {

    // ----------------------------------------
    // PRIVATE CLASS MEMBERS
    // ----------------------------------------
    private $database; /* Database class */
    // ----------------------------------------
    // PUBLIC CLASS MEMBERS
    // ----------------------------------------
    public $studentFactory; /* StudentFactory class */

    // ----------------------------------------
    // CONSTRUCTOR
    // ----------------------------------------
    public function __construct($database) {
        $this->database = $database;
        $this->studentFactory = new StudentFactory($database);
    }

    // ----------------------------------------
    // PUBLIC API METHODS
    // ----------------------------------------
    /**
     * Returns the leaderboard for all students sorted in ascending order
     * The result can be filtered by specifying the two <strong>optional</strong> parameters
     * @param <string> $filterBy - The type of the filter. Can be lecture or homework.
     * @param <string> $filterValue - The value for the filter (the lecture's or homework's number)
     * @return <array> of Student objects, sorted in ASC order
     */
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

    /**
     * Add points to a given student.
     * The student should exist in the database
     * @param <integer> $studentId - the unique student id from the database
     * @param <float> $points - the number of points to be added
     * @param <string> $type - the type of activity for the points.
     * Can be answer, question, homework, etc.
     * @param <int> $lecture - the number of the lecture thah the points apply to
     * @return - true on success. The Database class will throw an exception
     * if there are problems
     */
    public function addPoints($studentId, $points, $type, $lecture) {
        $sql = "INSERT INTO leaderboard(type, lecture, student_id, points)
                VALUES('%s', %d, %d, %f)";
        $sql = sprintf($sql, $type, $lecture, $studentId, $points);
        $this->database->query($sql);

        return true;
    }

    // ----------------------------------------
    // PRIVATE METHODS
    // ----------------------------------------
    /**
     * Helper method that returns the leaderboard for all students
     * sorted in ASC order
     * @return <array> of Student class items
     */
    private function leaderboardStudents() {
        $sql = "SELECT student_id, SUM(points) as SCORE
                FROM leaderboard
                GROUP BY student_id
                ORDER BY SCORE DESC";

        $res = $this->database->query($sql);
        return $this->createStudentsFromResult($res);
    }

    /**
     * Helper method that returns the leaderboard filtered by given lecture
     * @param <int> $lecture - the number of the lecture for which the leaderboard is required
     * @return <array> of Student class items
     */
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

    /**
     * Helper method that returns the leaderboard filtered by given homework
     * @param <int> $homework - the number of the homework for which the leaderboard is required
     * @return <array> of Student class items
     */
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

    /**
     * Helper method for creating Students from a given database result
     * @param <Database resource> $res
     * @return <array> of Student class items
     */
    private function createStudentsFromResult($res) {
        $students = array();
        while (($row = $this->database->fetchAssoc($res)) !== FALSE) {
            $currentStudent = $this->studentFactory->getById($row["student_id"]);
            $currentStudent->setScore($row["SCORE"]);
            $students[] = $currentStudent;
        }

        return $students;
    }

}