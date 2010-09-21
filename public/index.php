<?php

require_once dirname(dirname(__FILE__)) . '/application/One.php';

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('PS') || define('PS', PATH_SEPARATOR);

defined('ROOT_PATH') ||
    define('ROOT_PATH', realpath(dirname(dirname(__FILE__))));

defined('APPLICATION_ENV') ||
    ($env = getenv('APPLICATION_ENV')) ? define('APPLICATION_ENV', $env) :
        define('APPLICATION_ENV', 'production');

set_include_path(realpath(ROOT_PATH . DS . 'externals' . DS . 'libraries'));

One::setEnv(APPLICATION_ENV);
//var_dump(One::getConfig());

One::setDomain(One::getConfig('system/hostname'));
One::setBasePath(One::getConfig('system/base-url'));

try {
    One::app()->bootstrap()
        ->run();
} catch (Exception $e) {
    echo $e->getMessage();
}


