<?php

/**
 * Generic Captcha class that presents the user a several images to choose from
 * one of which is the correct one
 * @author RadoRado (a.k.a Rado)
 */
class VisualCaptcha {

    private $correctImageNames = array(); /* array of Strings */
    private $randomlyChoosenCorrect = "";
    private $imagesFolder = "";
    private $wrongParametersExceptionMessage = "You should give a image folder location and a set of correct image names";
    private $imagesFolderNotDir = "The provided image folder is not a directory";

    public function getWrongParametersExceptionMessage() {
        return $this->wrongParametersExceptionMessage;
    }

    public function getImageFolderNotDirExceptionMessage() {
        return $this->imagesFolderNotDir;
    }

    public function setCorrectImageNames($imageNames /* array of Strings */) {
        $this->correctImageNames = $imageNames;
    }

    public function setImagesFolder($folder) {
        $this->imagesFolder = $folder;
    }

    public function getImagesFolder() {
        return $this->imagesFolder;
    }

    public function generate($count = 0) {
        if ($this->imagesFolder == "" || count($this->correctImageNames) == 0) {
            throw new Exception($this->wrongParametersExceptionMessage);
        }

        $files = array();

        if (!is_dir($this->imagesFolder)) {
            throw new Exception($this->imagesFolderNotDir);
        }

        $it = new DirectoryIterator($this->imagesFolder);

        foreach ($it as $fileInfo) {
            if ($fileInfo->isFile() && !in_array($fileInfo->getFilename(), $this->correctImageNames)) {
                $files[] = $fileInfo->getFilename();
            }
        }
        if ($count > 0) {
            $randomIndexes = array_rand($files, $count);
            $newFiles = array();
            $len = count($randomIndexes);
            for ($i = 0; $i < $len - 1 /* because we need a place for the correct image*/; ++$i) {
                $newFiles[] = $files[$randomIndexes[$i]];
            }
            $files = $newFiles;
        }
        
        $files[] = $this->getRandomCorrectImage();
        shuffle($files);
        return $files;
    }

    public function getLastCorrect() {
        return $this->randomlyChoosenCorrect;
    }

    private function getRandomCorrectImage() {
        $this->randomlyChoosenCorrect = $this->correctImageNames[array_rand($this->correctImageNames)];
        return $this->randomlyChoosenCorrect;
    }

}