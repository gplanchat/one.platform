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
 * Administration abstract controller for grids and forms management
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */
abstract class One_Admin_Core_Controller_FormGridAbstract
    extends One_Admin_Core_ControllerAbstract
{
    protected $_collectionModel = null;

    /**
     *
     * @var One_Admin_Core_Block_Form
     */
    protected $_form = null;

    protected function _prepareGrid($gridName, $collectionModel, $sort = array())
    {
        $this->loadLayout('admin.grid');

        if (is_string($collectionModel)) {
            $this->_collectionModel = $this->app()->getModel($collectionModel);
        } else if ($this->_collectionModel === null) {
            $this->_collectionModel = $collectionModel;
        }

        $grid = $this->getLayout()
            ->getBlock('grid')
            ->setCollection($this->_collectionModel)
            ->loadColumns($gridName)
            ->setPage($this->_getParam('p'), $this->_getParam('n'))
            ->sort($sort)
        ;

        return $grid;
    }

    protected function _prepareForm()
    {
        $this->loadLayout('admin.form');

        $this->_form = $this->getLayout()
            ->getBlock('form')
        ;

        return $this;
    }

    public function addTab($configIdentitifer, $name, $label)
    {
        $this->_form->addTab($configIdentitifer, $name, $label);
    }

    abstract public function indexAction();

    abstract public function newAction();

    abstract public function newPostAction();

    abstract public function editAction();

    abstract public function editPostAction();

    abstract public function deleteAction();
}