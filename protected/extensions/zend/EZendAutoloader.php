<?php

class EZendAutoloader extends CApplicationComponent {
    public $basePath;

    public $customNamespaces = array();
    
    public function init() {
        $path = $this->basePath;
        if (strlen($path)) {
            if (strlen($path) > 1 && $path[strlen($path)-1] == '/') {
                $path = substr($path, 0, strlen($path)-1);
            }
            $this->addIncludePath($path);
            $path .= '/';
        }
        spl_autoload_unregister(array('YiiBase','autoload'));
 
        require_once($path.'Zend/Loader/Autoloader.php');
        $autoloader = Zend_Loader_Autoloader::getInstance();
 
        foreach ($this->customNamespaces as $namespace) {
            $autoloader->registerNamespace($namespace);
        }
        spl_autoload_register(array('YiiBase','autoload'));
    }

    protected function addIncludePath($path) {
        if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir')) {
            throw new CException(Yii::t('Include path {path} does not exist.', array('path'=>$path)));
        }
        $paths = explode(PATH_SEPARATOR, get_include_path());
        if (array_search($path, $paths) === false) {
            array_push($paths, $path);
        }
        set_include_path(implode(PATH_SEPARATOR, $paths));
    }
}

?>
