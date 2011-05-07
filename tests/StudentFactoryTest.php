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
        $this->studentFactory = new StudentFactory(self::$database);
    }

    public function testGetAllMethodOnEmptyDatabase() {
        $students = $this->studentFactory->getAll();

        $this->assertEquals(array(), $students);
    }

    public function testCreateMethodOnEmptyDatabase() {
        $name1 = "Rado1";
        $name2 = "Rado2";
        $name3 = "Rado3";

        $firstId = $this->studentFactory->createStudent($name1);
        $this->assertEquals(1, $firstId);

        $secondId = $this->studentFactory->createStudent($name2);
        $this->assertEquals(2, $secondId);

        $thirdId = $this->studentFactory->createStudent($name3);
        $this->assertEquals(3, $thirdId);
    }

}