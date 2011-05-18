<?php

/**
 * Helper class for navigating through pages
 *
 * @author RadoRado (a.k.a Rado)
 */
class Navigation {

    public static $INDEX_PAGE;
    public static $ADMIN_PAGE;
    
    public static function go($to) {
        header("Location: " . $to);
    }

}

Navigation::$INDEX_PAGE = "index.php";
Navigation::$ADMIN_PAGE = "adminPage.php";