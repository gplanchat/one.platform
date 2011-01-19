<?php

defined('ROOT_PATH') ||
    define('ROOT_PATH', realpath(dirname(dirname(__FILE__))));

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);

require_once ROOT_PATH . DS . 'application' . DS . 'One.php';

defined('APPLICATION_PATH') ||
    define('APPLICATION_PATH', ROOT_PATH . DS . 'application');

set_include_path(implode(PS, array(
    realpath(ROOT_PATH . DS . 'externals' . DS . 'libraries'),
    realpath(APPLICATION_PATH . DS . 'code' . DS . 'core'),
    realpath(APPLICATION_PATH . DS . 'code' . DS . 'community'),
    realpath(APPLICATION_PATH . DS . 'code' . DS . 'local')
    )));

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()
    ->registerNamespace('One_Core')
;

date_default_timezone_set('Europe/Paris');

try {
    One::app('setup')
        ->bootstrap()
        ->run()
    ;
} catch (Zend_Exception $e) {
    echo $e->getMessage();
}
