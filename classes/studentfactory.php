<?php
/**
 * Description of StudentFactory
 *
 * @author radoslav
 */
class StudentFactory extends AbstractFactory {

    public function getById($id) {
        $sql = "SELECT id,name,fn FROM students WHERE id = " . $id . " LIMIT 1";
        $res = $this->database->query($sql);
        $row = $this->database->fetch_array($res);

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
