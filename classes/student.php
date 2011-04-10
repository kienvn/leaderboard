<?php

class Student {

    public $id;
    public $name;
    public $facultyNumber;
    public $email;
    public $score = 0;

    public function __construct($id = -1, $name = "", $fn = NULL, $email = NULL) {
        $this->id = $id;
        $this->name = $name;
        $this->facultyNumber = $fn;
        $this->email = $email;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    static function compare($a, $b) {
        if ($a->score == $b->score) {
            return 0;
        }
        return ($a->score > $b->score) ? -1 : 1;
    }

}