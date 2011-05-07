<?php

/**
 * PHPUnit test for the StudentFactory class
 *
 * @author RadoRado (a.k.a Rado)
 */
require_once("php_unit_config.php");
require_once("config/testDatabase_config.php");

require_once("../classes/config.php");
require_once("../classes/databaseaware.php");
require_once("../classes/database.php");
require_once("../classes/studentfactory.php");

class StudentFactoryTest extends PHPUnit_Framework_TestCase {

    public static $database;
    protected $studentFactory;

    public static function setUpBeforeClass() {
        global $dbConfig;
        self::$database = new Database($dbConfig, true);
        // create the database tables
        passthru("nohup mysql -u {$dbConfig['DB_USER']} -p{$dbConfig['DB_PASS']} {$dbConfig['DB_NAME']} < data/radodevc_ldrboard.sql");
    }

    public static function tearDownAfterClass() {
        global $dbConfig;
        self::$database->query("DROP DATABASE " . $dbConfig["DB_NAME"]);
    }

    protected function setUp() {
        // todo
    }

    public function testSomething() {
        $this->assertEquals(TRUE, TRUE);
    }

}