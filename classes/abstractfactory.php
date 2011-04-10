<?php

/**
 * Description of abstractfactory
 * @author radoslav
 */

class AbstractFactory {
    protected $database;

    public function  __construct($database) {
        $this->database = $database;
    }
}
?>
