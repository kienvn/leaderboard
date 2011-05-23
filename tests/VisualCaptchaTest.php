<?php

/**
 * Description of VisualCaptchaTest
 *
 * @author RadoRado (a.k.a Rado)
 */
require_once("php_unit_config.php");
require_once("../classes/visualcaptcha.php");

class VisualCaptchaTest extends PHPUnit_Framework_TestCase {

    public function testGenerateMethodException() {
        $excepted = false;
        $vc = new VisualCaptcha();
        try {
            $vc->generate();
        } catch (Exception $exc) {
            $this->assertEquals($vc->getWrongParametersExceptionMessage(), $exc->getMessage());
            $excepted = true;
        }

        $this->assertEquals(true, $excepted);
    }

    public function testGenerateMethodExceptionWithProvidedImageFolder() {
        $excepted = false;
        $vc = new VisualCaptcha();
        $vc->setImagesFolder("images");
        try {
            $vc->generate();
        } catch (Exception $exc) {
            $this->assertEquals($vc->getWrongParametersExceptionMessage(), $exc->getMessage());
            $excepted = true;
        }

        $this->assertEquals(true, $excepted);
    }

    public function testGenerateMethodExceptionWithProvidedCorrectImageNames() {
        $excepted = false;
        $vc = new VisualCaptcha();
        $vc->setCorrectImageNames(array("img1.jpg"));
        try {
            $vc->generate();
        } catch (Exception $exc) {
            $this->assertEquals($vc->getWrongParametersExceptionMessage(), $exc->getMessage());
            $excepted = true;
        }

        $this->assertEquals(true, $excepted);
    }

    public function testGenerateMethodExceptionWhereImageFolderIsNotDir() {
        $excepted = false;
        $vc = new VisualCaptcha();
        $vc->setImagesFolder("phpunit.xml");
        $vc->setCorrectImageNames(array("img1.jpg"));
        try {
            $vc->generate();
        } catch (Exception $exc) {
            $this->assertEquals($vc->getImageFolderNotDirExceptionMessage(), $exc->getMessage());
            $excepted = true;
        }

        $this->assertEquals(true, $excepted);
    }

    public function testGenerateMethodGetImagesFolder() {
        $vc = new VisualCaptcha();
        $imagesFolder = "images";
        $vc->setImagesFolder($imagesFolder);
        $vc->setCorrectImageNames(array("wtf.png"));

        $this->assertEquals($imagesFolder, $vc->getImagesFolder());
    }

    public function testGenerateMethodWithCorrectDataWithoutCount() {
        $vc = new VisualCaptcha();
        $correct = array("answer_btn.png", "answer_btn50p.png");
        $vc->setImagesFolder("images");

        $vc->setCorrectImageNames($correct);

        $files = $vc->generate();

        $this->assertTrue(true, in_array($vc->getLastCorrect(), $files));
        $this->assertTrue(true, in_array($vc->getLastCorrect(), $correct));
    }

    public function testGenerateMethodWithCorrectDataWithoutCountWithSameNumberCorrectImagesAsAll() {
        $vc = new VisualCaptcha();
        $correct = scandir("images");
        $vc->setImagesFolder("images");

        $vc->setCorrectImageNames($correct);

        $files = $vc->generate();

        $this->assertEquals(1, count($files));
        $this->assertTrue(true, in_array($vc->getLastCorrect(), $files));
        $this->assertTrue(true, in_array($vc->getLastCorrect(), $correct));
    }

    public function testGenerateMethodWithCorrectDataWithCount() {
        $vc = new VisualCaptcha();
        $correct = array("answer_btn.png", "answer_btn50p.png");
        $vc->setImagesFolder("images");

        $vc->setCorrectImageNames($correct);

        $count = 4;
        $files = $vc->generate($count);

        $this->assertEquals($count, count($files));
        $this->assertTrue(true, in_array($vc->getLastCorrect(), $files));
        $this->assertTrue(true, in_array($vc->getLastCorrect(), $correct));
    }

}