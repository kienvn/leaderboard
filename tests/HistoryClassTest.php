<?php

/**
 * Description of HistoryClassTest
 *
 * @author RadoRado (a.k.a Rado)
 */
require_once("php_unit_config.php");
require_once("../classes/history.php");

class HistoryClassTest extends PHPUnit_Framework_TestCase {

    public function testEmptyHistoryObject() {
        $h = new History();
        $this->assertEquals(-1, $h->lecture);
        $this->assertEquals(0, $h->points);
        $this->assertEquals("", $h->type);
    }

    public function testStandartHistoryObject() {
        $lecture = 1;
        $points = 10;
        $type = "homework";
        $h = new History($lecture, $points, $type);
        $this->assertEquals($lecture, $h->lecture);
        $this->assertEquals($points, $h->points);
        $this->assertEquals($type, $h->type);
    }

}
