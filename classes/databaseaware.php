<?php

/**
 * Abstract class in order
 *
 * @author RadoRado (a.k.a Rado)
 */
abstract class DatabaseAware {

    protected $database;

    public function __construct($database) {
        $this->database = $database;
    }

}
