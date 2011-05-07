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
    protected $arrayOfStudents;
    protected $studentCount = 50;

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
        $this->arrayOfStudents = array();

        for ($i = 0; $i < $this->studentCount; ++$i) {
            $s = new Student();
            $s->name = "Rado" . $i;
            $s->id = $i + 1;
            $this->arrayOfStudents[] = $s;
        }
    }

    public function testGetAllMethodOnEmptyDatabase() {
        $students = $this->studentFactory->getAll();

        $this->assertEquals(array(), $students);
    }

    public function testGetByIdMethodOnEmptyDatabase() {
        for ($i = 0; $i < $this->studentCount; ++$i) {
            $id = $i + 1;
            $resultedStudent = $this->studentFactory->getById($id);
            $this->assertEquals(NULL, $resultedStudent);
        }
    }

    public function testCreateMethodOnEmptyDatabase() {
        for ($i = 0; $i < $this->studentCount; ++$i) {
            $name = $this->arrayOfStudents[$i]->name;
            $id = $this->studentFactory->createStudent($name);
            $this->assertEquals(($i + 1), $id);
        }
    }

    public function testGetAllMethodOnNonEmptyDatabaseAfterCreateMethod() {
        $students = $this->studentFactory->getAll();

        $this->assertEquals($this->arrayOfStudents, $students);
    }

    public function testGetByIdMethodOnNonEmptyDatabaseAfterCreateMethod() {
        for ($i = 0; $i < $this->studentCount; ++$i) {
            $id = $i + 1;
            $resultedStudent = $this->studentFactory->getById($id);
            $this->assertEquals($this->arrayOfStudents[$i], $resultedStudent);
        }
    }

}