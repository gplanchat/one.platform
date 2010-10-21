<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * NOTICE:
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

/**
 * Base escape helper class
 *
 * @access      public
 * @author      gplanchat
 * @category    Helper
 * @package     One
 * @subpackage  One_core
 */
class One_Core_Helper_Escape
    extends One_Core_HelperAbstract
{
    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @param string $data
     * @return string
     */
    public function htmlEscape($data)
    {
        return htmlspecialChars($data, ENT_NOQUOTES, self::DEFAULT_CHARSET);
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @param string $data
     * @return string
     */
    public function urlEscape($data)
    {
        return urlencode($data);
    }

    /**
     * FIXME: PHPDoc
     *
     * @since 0.1.0
     *
     * @param string $data
     * @return string
     */
    public function jsStringEscape($data, $quote = "'")
    {
        return str_replace($quote, '\\'.$quote, $data);
    }
}