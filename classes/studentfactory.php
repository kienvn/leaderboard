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
        $row = $this->database->fetch_array($res);
        return $this->createStudentFromRow($row);
    }

    public function getAll() {
        $sql = "SELECT id,name,fn FROM students";
        $res = $this->database->query($sql);

        $students = array();
        while(($row = $this->database->fetch_array($res)) !== FALSE) {
            $students[] = $this->createStudentFromRow($row);
        }
        return $students;
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
