<?php

/**
 * Value object class that holds the information for a single student
 * Students are compared by score
 */
class Student {

    // ----------------------------------------
    // PUBLIC CLASS MEMBERS
    // ----------------------------------------
    public $id; /*database id*/
    public $name;
    public $score;

    // ----------------------------------------
    // CONSTRUCTOR
    // ----------------------------------------
    public function __construct($id = -1, $name = "") {
        $this->id = $id;
        $this->name = $name;
        $this->score = 0.0;
    }

    // ----------------------------------------
    // PUBLIC API METHODS
    // ----------------------------------------

    public function setScore($score) {
        $this->score = $score;
    }

    // ----------------------------------------
    // STATIC METHODS
    // ----------------------------------------
    static function compare($a, $b) {
        if ($a->score == $b->score) {
            return 0;
        }
        return ($a->score > $b->score) ? -1 : 1;
    }

}