<?php

defined('ROOT_PATH') ||
    define('ROOT_PATH', realpath(dirname(dirname(dirname(__FILE__)))));

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);

require_once ROOT_PATH . DS . 'application' . DS . 'One.php';

defined('APPLICATION_PATH') ||
    define('APPLICATION_PATH', ROOT_PATH . DS . 'application');

defined('APPLICATION_ENV') ||
    ($env = getenv('APPLICATION_ENV')) ? define('APPLICATION_ENV', $env) :
        define('APPLICATION_ENV', null);

set_include_path(implode(PS, array(
    realpath(ROOT_PATH . DS . 'externals' . DS . 'libraries'),
    realpath(APPLICATION_PATH . DS . 'code' . DS . 'core'),
    realpath(APPLICATION_PATH . DS . 'code' . DS . 'community'),
    realpath(APPLICATION_PATH . DS . 'code' . DS . 'local'),
    implode(PS, glob(ROOT_PATH . DS . 'test' . DS . 'externals' . DS . 'libraries' . DS . '*'))
    )));

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()
    ->registerNamespace('One_Core')
    ->registerNamespace('Test_')
    ->registerNamespace('PHPUnit_')
    ->registerNamespace('vfs')
;

Test_AllTests::suite()->run();
