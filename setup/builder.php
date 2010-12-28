<?php

set_time_limit(0);

define('DS', DIRECTORY_SEPARATOR);

define('RP', realpath(dirname(dirname(__FILE__)) . DS . 'application' . DS . 'code' . DS . 'core' . DS . 'One' . DS . 'Core'));
define('ZP', realpath(dirname(dirname(__FILE__)) . DS . 'externals' . DS . 'libraries' . DS . 'Zend'));

function addDirectoryContent(Phar $phar, $localPath = null, $pharPath = null) {
    foreach (new DirectoryIterator($localPath) as $child) {
        if ($child->isDot()) {
            continue;
        }
        if ($child->isDir()) {
            addDirectoryContent($phar, $localPath . DS . $child->getFilename(), $pharPath . '/' . $child->getFilename());
        } else {
            $phar->addFile($localPath . DS . $child->getFilename(), $pharPath . '/' . $child->getFilename());
            echo 'Added file "' . $pharPath . '/' . $child->getFilename() . '"' . PHP_EOL;
        }
    }
}
ini_set('html_errors', false);
ini_set('output_buffering', false);
header('Content-Type: text/plain');

$phar = new Phar('one-platform-setup-1.0.0.phar');

$phar->setStub(file_get_contents(dirname(__FILE__) . DS . 'stub.php'));

$phar->addEmptyDir('application');
$phar->addEmptyDir('application/code');
$phar->addEmptyDir('application/code/core');
$phar->addEmptyDir('application/code/core/One');
$phar->addEmptyDir('application/code/core/One/Core');
$phar->addEmptyDir('externals');
$phar->addEmptyDir('externals/libraries');
$phar->addEmptyDir('externals/libraries/Zend');

$phar->addFile(dirname(dirname(__FILE__)) . DS . 'application' . DS . 'One.php', 'application/One.php');

addDirectoryContent($phar, RP, 'application/code/core/One/Core');
//addDirectoryContent($phar, ZP, 'externals/libraries/Zend');

//$phar->compressFiles(Phar::BZ2);

