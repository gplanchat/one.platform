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

/**
 * @deprecated
 * @param unknown_type $query
 * @param unknown_type $table
 * @param unknown_type $fetch
 */
function doquery($query, $table, $fetch = false)
{
    static $instance = null;
    static $prefix = null;

    /*
     * Throw notices on doquery() calls.
     */
    if (defined('DEPRECATION')) {
        $backtrace = debug_backtrace();
        $message = "Function doquery() called from file '%s', line %d.";
        trigger_error(sprintf($message, $backtrace[0]['file'], $backtrace[0]['line']),
            $fetch ? E_USER_NOTICE : E_USER_WARNING);
    }

    if ($instance === null) {
        $config = simplexml_load_file(implode(DIRECTORY_SEPARATOR, array(dirname(ROOT_PATH), 'application', 'configs', 'local.xml')));
        $dbConfig = $config->default->general->database->connection->core_setup->params;

        $instance = mysql_connect((string) $dbConfig->host, (string) $dbConfig->username, (string) $dbConfig->password);
        mysql_select_db((string) $dbConfig->dbname, $instance);

        $prefix = (string) $dbConfig->{'table-prefix'};
    }

    $sql = str_replace('{{table}}', $prefix.'legacies_'.$table, $query);

    try {
        /**
         * @var Zend_Db_Statement_Abstract
         */
        if (!($statement = mysql_query($sql, $instance))) {
            throw new Exception(mysql_error($instance));
        }
    } catch (Exception $e) {
        trigger_error($e->getMessage() . PHP_EOL . "<br /><pre></code>$sql<code></pre><br />" . PHP_EOL, E_USER_WARNING);
    }


    if (func_num_args() < 3) {
        $backtrace = debug_backtrace();
        $message = "Function doquery() called from file '%s', line %d, without the 3rd parameter set to true.";

        !defined('DEPRECATION') || trigger_error(sprintf($message, $backtrace[0]['file'], $backtrace[0]['line']),
            defined('E_USER_DEPRECATED') ? E_USER_DEPRECATED : E_USER_WARNING);

        return $statement;
    } else {
        return mysql_fetch_array($statement);
    }
}
