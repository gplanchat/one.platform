<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

if (version_compare(phpversion(), '5.2.0', '<') === true) {
    echo  <<<EOF
<html>
  <head>
    <title>XNova:Legacies - PHP version</title>
  </head>
  <body>
  <div style="font: 12px/1.35em arial, helvetica, sans-serif;">
    <div style="margin: 0 0 25px 0; border-bottom: 1px solid #CCCCCC;">
      <h3 style="margin: 0; font-size: 1.7em; font-weight: normal; text-transform:none; text-align: left; color: #2f2f2f;">
      Whoops, it looks like you have an invalid PHP version
      </h3>
    </div>
    <p>XNova:Legacies supports PHP 5.2.0 or newer. Please update your PHP version.</p>
  </div>
  </body>
</html>
EOF;
    exit(0);
}

defined('ROOT_PATH') ||
    define('ROOT_PATH', realpath(dirname(dirname(__FILE__))));

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
    realpath(APPLICATION_PATH . DS . 'code' . DS . 'local')
    )));

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()
    ->registerNamespace('One_Core')
;

date_default_timezone_set('Europe/Paris');

/**
 * Set up environment
 */
defined('DEBUG') || define('DEBUG',
    ($env = strtolower(getenv('DEBUG'))) === '1' || $env === 'true' || $env === 'on');
defined('DEPRECATION') || define('DEPRECATION',
    ($env = strtolower(getenv('DEPRECATION'))) === '1' || $env === 'true' || $env === 'on');

try {
    One::setDefaultWebsiteId('frontoffice');
    One::app(null, APPLICATION_ENV);
} catch (Zend_Exception $e) {
    echo $e->getMessage();
    die();
}

/**
 * Mute the legacy coding errors reporting
 * @deprecated
 */
if (!defined('DEBUG')) {
//    @ini_set('display_errors', false);
} else {
//    @ini_set('display_errors', true);
}

/**
 * @var string the php extension used for the files
 * @deprecated
 */
define('PHPEXT', require 'extension.inc');

/**
 * @var the current game version.
 * @deprecated
 */
define('VERSION', One::app()->getConfig('modules/Legacies/version'));

$game_config    = One::app()->getSingleton('legacies/config');
$user           = One::app()->getSingleton('user/session')->getUserEntity();
$lang           = array();
$IsUserChecked  = false;

define('DEFAULT_SKINPATH', 'skins/xnova/');
define('TEMPLATE_DIR', realpath(ROOT_PATH . DS . 'templates' . DS));
define('TEMPLATE_NAME', 'OpenGame');
define('DEFAULT_LANG', 'fr_FR');

$debug = One::app()->getSingleton('legacies/debug');

require dirname(__FILE__) . DS . 'includes/constants.php';
require dirname(__FILE__) . DS . 'includes/functions.php';
require dirname(__FILE__) . DS . 'includes/unlocalised.php';
require dirname(__FILE__) . DS . 'includes/todofleetcontrol.php';
require dirname(__FILE__) . DS . 'language' . DS . DEFAULT_LANG . DS . 'lang_info.cfg';
require dirname(__FILE__) . DS . 'includes/vars.php';
require dirname(__FILE__) . DS . 'includes/strings.php';

if (!defined('DISABLE_IDENTITY_CHECK')) {
    if ($game_config->getGameDisable() && $user->getAuthlevel() < 2) {
        message(stripslashes($game_config->getCloseReason()), $game_config->getGameName());
    }
}

//if (!$user->getId() && !defined('DISABLE_IDENTITY_CHECK')) {
//    header('HTTP/1.1 401 Unauthorized');
//    header('Location: account/login');
//    exit(0);
//}

includeLang('system');
includeLang('tech');

$fleets = One::app()
    ->getSingleton('legacies/fleet.collection')
    ->addFilters(array(
        One_Core_Bo_CollectionAbstract::FILTER_OR => array(
            One_Core_Bo_CollectionAbstract::FILTER_LOWER_THAN_OR_EQUAL => array(
                'fleet_start_time' => time()
                ),
            One_Core_Bo_CollectionAbstract::FILTER_LOWER_THAN_OR_EQUAL => array(
                'fleet_end_time' => time()
                )
            )
        ))
    ->load()
;

var_dump($fleets->toArray());
die();

//require dirname(__FILE__) . DS . 'rak.php');

if ($user->getId()) {
    foreach ($fleets as $fleet) {
        FlyingFleetHandler($fleet);
    }

    if (!defined('IN_ADMIN')) {
        $dpath = (isset($user['dpath']) && !empty($user['dpath'])) ? $user['dpath'] : DEFAULT_SKINPATH;
    } else {
        $dpath = '../' . DEFAULT_SKINPATH;
    }

    SetSelectedPlanet($user);
/*
    $planetrow = $readConnection->select()
        ->from($readConnection->getDeprecatedTable('planets'))
        ->where('id=?', $user['current_planet'])
        ->query()
        ->fetch()*/
    ;
/*
    $galaxyrow = $readConnection->select()
        ->from($readConnection->getDeprecatedTable('planets'))
        ->where('id=?', $planetrow['id'])
        ->query()
        ->fetch()*/
    ;
}

