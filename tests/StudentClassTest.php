<?php

/**
 * PHPUnit for the Student class
 *
 * @author RadoRado (a.k.a Rado)
 */
require_once("php_unit_config.php");
require_once("../classes/student.php");

class StudentClassTest extends PHPUnit_Framework_TestCase {

    public function testEmptyStudentObject() {
        $student = new Student();

        $this->assertEquals($student->email, "");
        $this->assertEquals($student->facultyNumber, "");
        $this->assertEquals($student->id, -1);
        $this->assertEquals($student->score, 0);
        $this->assertEquals($student->name, "");
    }

    public function testNonEmptyWithStandartDataStudentObject() {
        $id = 1;
        $fn = "80458";
        $email = "student@uni.com";
        $name = "Student Student";
        $score = 15;

        $student = new Student($id, $name, $fn, $email);
        $student->setScore($score);

        $this->assertEquals($student->email, $email);
        $this->assertEquals($student->facultyNumber, $fn);
        $this->assertEquals($student->id, $id);
        $this->assertEquals($student->score, $score);
        $this->assertEquals($student->name, $name);
    }

    public function testStaticMethodForComparingStudentObjects() {
        $s1 = new Student();
        $s1->setScore(10);

        $s2 = new Student();
        $s2->setScore(15);

        $this->assertEquals(Student::compare($s1, $s2), 1);

        $s1->setScore(20);
        $this->assertEquals(Student::compare($s1, $s2), -1);

        $s1->setScore(15);
        $this->assertEquals(Student::compare($s1, $s2), 0);
    }

}