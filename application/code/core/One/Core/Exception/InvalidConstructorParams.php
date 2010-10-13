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
 * Invalid method call exception, thrown when :
 *  - at least one constructor param is missing or is invalid
 *
 * @access      public
 * @author      gplanchat
 * @category    Exception
 * @package     One
 * @subpackage  One_Core
 */
class One_Core_Exception_InvalidConstructorParams
    extends InvalidArgumentException
    implements One_Core_Exception
{
    private $_previous = null;

    public function __construct($message, $code = null, $previous = null)
    {
        if (version_compare(phpversion(), '5.3.1', '<=')) {
            parent::__construct($message, $code);
            $this->_previous = $previous;
        } else {
            parent::__construct($message, $code, $previous);
        }
    }

    private function _getPrevious()
    {
        return $this->_previous;
    }

    public function __call($method, $params)
    {
        if ($method === 'getPrevious') {
            return $this->_getPrevious();
        }
    }
}
