<?php

new Phar(__FILE__, 0, basename(__FILE__));

defined('ROOT_PATH') ||
    define('ROOT_PATH', 'phar://' . basename(__FILE__));

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);

require_once ROOT_PATH . '/application/One.php';

defined('APPLICATION_PATH') ||
    define('APPLICATION_PATH', ROOT_PATH . DS . 'application');

set_include_path(implode(PATH_SEPARATOR, array(
    'phar://' . basename(__FILE__) . '/externals/libraries',
    'phar://' . basename(__FILE__) . '/application/code/core',
    'phar://' . basename(__FILE__) . '/application/code/community',
    'phar://' . basename(__FILE__) . '/application/code/local',
    get_include_path()
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

__HALT_COMPILER(); ?>