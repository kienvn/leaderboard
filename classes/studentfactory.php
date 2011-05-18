<?php

/**
 * This class is responsible for different logic related to the players (students)
 *
 * @author radoslav
 */
class StudentFactory extends DatabaseAware {

    // ----------------------------------------
    // PUBLIC METHODS
    // ----------------------------------------
    
    /**
     * Returns a student that matches the given id
     * @param <int> $id - the id of the student in the database
     * @return <Student> returns a newly Created Student object but without the points
     * NULL is returned if no student is found
     */
    public function getById($id) {
        $sql = "SELECT id,name,fn,email
                FROM students
                WHERE id = " . $id . " LIMIT 1";
        $res = $this->database->query($sql);
        $row = $this->database->fetchAssoc($res);
        if (!$row) {
            return NULL;
        }
        return $this->createStudentFromRow($row);
    }

    /**
     * Fetches all records for the students
     * @return <array of Student objects>
     */
    public function getAll() {
        $sql = "SELECT id,name,fn,email
                FROM students";
        $res = $this->database->query($sql);

        $students = array();
        while (($row = $this->database->fetchAssoc($res)) !== FALSE) {
            $students[] = $this->createStudentFromRow($row);
        }
        return $students;
    }

    /**
     * Returns a student's id from the database by its name
     * @param <string> $name - the name of the searched student
     * @return <int> returns the found id
     * or -1 if not found
     */
    public function getIdByName($name) {
        $sql = "SELECT COUNT(id) AS OK, id
                FROM students
                WHERE name = '%s'
                LIMIT 1";
        $sql = sprintf($sql, $name);
        $res = $this->database->query($sql);
        $row = $this->database->fetchAssoc($res);

        return $row["OK"] == 1 ? $row["id"] : -1;
    }

    /**
     * Creates a new record for a student in the database
     * @param <string> $name - the name of the student
     * @param <string> $fn - the faculty number of the student
     * @param <string> $email - the email of the student
     * @return <int> - the newly created id
     */
    public function createStudent($name, $fn, $email) {
        $sql = "INSERT INTO students(name,fn,email)
                VALUES('%s', '%s', '%s')";
        $sql = sprintf($sql, $name, $fn, $email);
        $this->database->query($sql);
        // get the newly created ID
        return $this->database->lastInsertedId();
    }

    /**
     * Deletes a student from the database and all related to him information
     * The deletion is made via a database transaction
     * @param <int> $studentId
     * @return <bool> TRUE if deleted or FALSE if it was not found or any problem occured
     * If there is a problem, the transaction is rollbacked
     */
    public function deleteStudent($studentId) {
        $sqlStudents = "DELETE FROM students
                        WHERE id = %d
                        LIMIT 1";
        $sqlStudents = sprintf($sqlStudents, $studentId);

        $sqlLeaderboard = "DELETE FROM leaderboard 
                           WHERE student_id = %d";
        $sqlLeaderboard = sprintf($sqlLeaderboard, $studentId);

        $sqlAdministrative = "DELETE FROM administrative 
                              WHERE student_id = %s
                              LIMIT 1";
        $sqlAdministrative = sprintf($sqlAdministrative, $studentId);

        // begin transaction so no data can be lost
        $this->database->query("START TRANSACTION");
        $res1 = $this->database->query($sqlStudents);
        $res2 = $this->database->query($sqlLeaderboard);
        $res3 = $this->database->query($sqlAdministrative);

        $finalRes = $res1 && $res2 && $res3;
        if ($finalRes == TRUE) {
            $this->database->query("COMMIT");
            return TRUE;
        } else {
            $this->database->query("ROLLBACK");
            return FALSE;
        }
    }

    /**
     * Not implemented yet
     * @param <string> $mergeThis - the name of the merging student
     * @param <string> $intoThis - the name of the original student
     * @return <bool>
     */
    public function mergeStudents($mergeThis, $intoThis) {
        if ($mergeThis == $intoThis) {
            return FALSE;
        }

        $mergeThisId = $this->getIdByName($mergeThis);
        if ($mergeThisId == -1) {
            return FALSE;
        }

        $intoThisId = $this->getIdByName($intoThis);
        if ($intoThisId == -1) {
            return FALSE;
        }
    }

    // ----------------------------------------
    // PRIVATE METHODS
    // ----------------------------------------
    private function createStudentFromRow($row) {
        return new Student(
                (int) $this->extract($row, "id"),
                $this->extract($row, "name"),
                $this->extract($row, "fn"),
                $this->extract($row, "email") // no email for now :)
        );
    }

    private function extract($from, $what) {
        return $from[$what];
    }

}