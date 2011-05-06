<?php
/**
 * Value Object in order to hold student's points history
 *
 * @author RadoRado (a.k.a Rado)
 */
class History {
    public $lecture; // int
    public $points; // double
    public $type; // string

    public function  __construct($lecture = -1, $points = 0, $type = "") {
        $this->lecture = $lecture;
        $this->points = $points;
        $this->type = $type;
    }
}
?>
