<?php

/**
 * Helper class for navigatin through pages
 *
 * @author RadoRado (a.k.a Rado)
 */
class Navigation {

    public static function go($to) {
        header("Location: " . $to);
    }

}