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

require_once 'Legacies/Database.php';

function doquery($query, $table, $fetch = false)
{
    /*
     * Throw notices on doquery() calls.
     */
    if (defined(DEPRECATION)) {
        $backtrace = debug_backtrace();
        $message = "Function doquery() called from file '%s', line %d.";
        trigger_error(sprintf($message, $backtrace[0]['file'], $backtrace[0]['line']),
            $fetch ? E_USER_NOTICE : E_USER_WARNING);
    }

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    $instance = Legacies_Database::getInstance();

    $sql = str_replace('{{table}}', Legacies_Database::getDeprecatedTable($table), $query);

    if($fetch) {
        try {
            /**
             * @var Zend_Db_Statement_Abstract
             */
            $statement = $instance->query($sql);
        } catch (Zend_Db_Exception $e) {
            trigger_error($e->getMessage() . PHP_EOL . "<br /><pre></code>$sql<code></pre><br />" . PHP_EOL, E_USER_WARNING);
        }

        return $statement->fetch(Zend_Db::FETCH_BOTH);
    } else {
        if (($statement = mysql_query($sql, $instance->getConnection())) === false) {
            trigger_error(mysql_error($instance->getConnection()) . PHP_EOL . "<br /><pre></code>$sql<code></pre><br />" . PHP_EOL, E_USER_WARNING);
        }
        return $statement;
    }
}
