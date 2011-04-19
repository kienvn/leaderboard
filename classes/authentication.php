<?php

ob_start();
session_start();

/**
 * Base class that provides simple authentication for the admin page
 *
 * @author RadoRado (a.k.a Rado)
 */
class Authentication extends DatabaseAware {

    private $salt = "%*4!#;\.k~'(_@";

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    public function login($userId, $password) {
        $password = $this->encrypt($password);

        $sql = "SELECT COUNT(student_id) as OK
                FROM administrative
                WHERE student_id = %d AND password = '%s'
                LIMIT 1";
        $sql = sprintf($sql, $userId, $password);
        $res = $this->database->query($sql);
        $row = $this->database->fetch_array($res);

        if ($row["OK"] == 1) {
            unset($_SESSION["userId"]);
            session_regenerate_id();
            $_SESSION["userId"] = $userId;
            $this->updateLoginTime($userId);
            return true;
        }

        return false;
    }

    public function checkLogin() {
        return
        isset($_SESSION["userId"])
        &&
        $_SESSION["userId"] > 0;
    }

    public function logout() {
        unset($_SESSION["userId"]);
        session_destroy();
        header("Location : index.php");
    }

    public function elevate($studentId, $password) {
        $password = $this->encrypt($password);
        $sql = "INSERT INTO administrative(student_id, password, last_login)
                VALUES(%d, '%s', '%s')";

        $lastDateTime = $this->getMysqlDateTime();
        $sql = sprintf($sql, $studentId, $password, $lastDateTime);
        $this->database->query($sql);

        return $this->database->affected_rows == 1;
    }

    public function encrypt($string) {
        // some random salt method from php.net
        $salt = md5($string . $this->salt);
        $string = md5("$salt$string$salt");
        return $string;
    }

    private function updateLoginTime($studentId) {
        $sql = "UPDATE administrative
                SET last_login = '%s' 
                WHERE student_id = %d
                LIMIT 1";
        $sql = sprintf($sql, $this->getMysqlDateTime(), $studentId);
        $this->database->query($sql);
    }

    private function getMysqlDateTime() {
        date("Y-m-d H:i:s", time());
    }

}