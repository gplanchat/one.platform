<?php
/**
 * This file is part of One.Platform
 *
 * @license Modified BSD
 * @see https://github.com/gplanchat/one.platform
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     - Neither the name of Zend Technologies USA, Inc. nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing One.Platform.
 *
 */

/**
 * HTML global block
 *
 * @uses        One_Core_Object
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
class One_Core_Block_Html_Page
    extends One_Core_Block_Html
{
    protected $_bodyClasses = array();

    protected function _construct($options)
    {
        $this->doctype(Zend_View_Helper_Doctype::XHTML5);

        $this->headMeta(array(
            'http-equiv' => 'Content-Type',
            'content'    => 'text/html; charset=utf-8'
            ));

        return parent::_construct($options);
    }

    public function addBodyClass($class)
    {
        if (is_string($class)) {
            $this->_bodyClasses[] = $class;
        } else if (is_array($class)) {
            foreach ($class as $classItem) {
                $this->_bodyClasses[] = $classItem;
            }
        }
        return $this;
    }

    public function setBodyClasses($classesList)
    {
        $this->_bodyClasses = $classesList;

        return $this->_bodyClasses;
    }

    public function getBodyClasses()
    {
        return $this->_bodyClasses;
    }

    public function renderBodyClasses()
    {
        return implode(' ', $this->getBodyClasses());
    }
}