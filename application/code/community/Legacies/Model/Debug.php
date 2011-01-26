<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
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

/**
 * @deprecated
 * @todo Clean up source code
 */
class Legacies_Model_Debug
    extends One_Core_Object
{
    protected $_logger = null;

    protected $_logMessages = array();

    public function _construct($options)
    {
        parent::_construct($options);

        $db = $this->app()
            ->getSingleton('core/database.connection.pool')
            ->getConnection('legacies_write')
        ;

        $this->_logger = new Zend_Log();
        $this->_logger
            ->addWriter(new Zend_Log_Writer_Firebug())
            ->addWriter(new Zend_Log_Writer_Db($db, $db->getTable('legacies/errors'), array(
                'error_type'   => 'priority',
                'error_text'   => 'message',
                'error_time'   => 'timestamp'
                )))
        ;
    }

    public function log($message)
    {
        $this->_logger->log('Test', Zend_Log::ALERT);
        $this->_logMessages[] = $message;
    }

    /**
     * @deprecated
     * @param string $message
     * @return void
     */
    public function add($message)
    {
        $this->log($message, Zend_Log::ERR);
    }

    /**
     * @deprecated
     * @return void
     */
    public function echo_log()
    {
        $messages = implode(PHP_EOL, $this->_logMessages);
        echo  <<<EOF
<dl class="k">
  <dt>
    <a href="admin/settings.php">Debug Log</a>:
  </dt>
  <dd>
    <pre><code>{$messages}</code></pre>
  </dd>
</dl>
EOF;
        die();
    }

    /**
     * @deprecated
     * @todo Clean up source code
     * @param $message
     * @param $title
     * @return unknown_type
     */
    public function error($message, $title)
    {
        if(defined('DEBUG')){
            echo <<<ERROR_EOF
<h2>$title</h2>
<div>
  <p><span style="color:red">$message</span></p>
  {$this->echo_log()}
</div>
ERROR_EOF;
        }

        if (!function_exists('message')) {
            echo "Une erreur s'est produite, merci de contacter l'admin.";
        } else {
            message("Une erreur s'est produite, merci de contacter l'admin.", "Erreur");
        }
    }
}
