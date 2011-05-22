<?php

/**
 * Global singleton class that represents the folder structure
 *
 * @author RadoRado (a.k.a Rado)
 */
class FolderStructure {

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new FolderStructure();
        }

        return self::$instance;
    }

    private $folders = array(
        "getJavaScriptFolder" => "javascript",
        "getCSSFolder" => "styles",
        "getTemplatesFolder" => "templates",
        "getPhpClassesFolder" => "classes",
        "getImagesFolder" => "images"
    );

    public function getJavaScriptFolder() {
        return $this->folders[__FUNCTION__];
    }

    public function getCSSFolder() {
        return $this->folders[__FUNCTION__];
    }

    public function getTemplatesFolder() {
        return $this->folders[__FUNCTION__];
    }

    public function getPhpClassesFolder() {
        return $this->folders[__FUNCTION__];
    }

    public function getImagesFolder() {
        return $this->folders[__FUNCTION__];
    }

    protected function __construct() {
        
    }

}
