<?php

/**
 * Description of StudentFactory
 *
 * @author radoslav
 */
class StudentFactory extends DatabaseAware {

    public function getById($id) {
        $sql = "SELECT id,name,fn FROM students WHERE id = " . $id . " LIMIT 1";
        $res = $this->database->query($sql);
        $row = $this->database->fetchAssoc($res);
        return $this->createStudentFromRow($row);
    }

    public function getAll() {
        $sql = "SELECT id,name,fn FROM students";
        $res = $this->database->query($sql);

        $students = array();
        while (($row = $this->database->fetchAssoc($res)) !== FALSE) {
            $students[] = $this->createStudentFromRow($row);
        }
        return $students;
    }

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

    public function createStudent($name) {
        $sql = "INSERT INTO students(name) VALUES('%s')";
        $sql = sprintf($sql, $name);
        $this->database->query($sql);
        // get the newly created ID
        return $this->database->insert_id();
    }

    private function createStudentFromRow($row) {
        return new Student(
                $this->extract($row, "id"),
                $this->extract($row, "name"),
                $this->extract($row, "fn"),
                NULL // no email for now :)
        );
    }

    private function extract($from, $what) {
        return $from[$what];
    }

}

?>
