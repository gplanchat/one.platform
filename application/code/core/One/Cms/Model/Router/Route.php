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
 *     - Neither the name of Grégory PLANCHAT nor the names of its
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
 * CMS-specific route handler
 *
 * @access      public
 * @author      gplanchat
 * @category    Cms
 * @package     One_Cms
 * @subpackage  One_Cms
 */
class One_Cms_Model_Router_Route
    extends Zend_Controller_Router_Route_Abstract
    implements One_Core_Model_Router_RouteInterface
{
    protected $_app = null;
    protected $_bo = null;

    protected $_urlVariable = ':';
    protected $_urlDelimiter = '/';
    protected $_regexDelimiter = '#';
    protected $_defaultRegex = null;

    public function __construct($moduleName, Array $routeConfig = array(), One_Core_Model_Application $app = null)
    {
        $this->_app = $app;
        $this->_bo = $this->app()->getSingleton('cms/page');
    }

    public function assemble($data = array(), $reset = false, $encode = false)
    {
        if (isset($data['page-id'])) {
        }
        $this->app()->throwException('core/unimplemented');
    }

    public function match($path, $partial = false)
    {
        $pathInfo = $path->getPathInfo();
        if (!$partial) {
            $pathInfo = trim($pathInfo, $this->_urlDelimiter);
        }

        $this->_bo->load($pathInfo, 'path');
        if (!$this->_bo->getId()) {
            return false;
        }

        $this->setMatchedPath($path->getPathInfo());

        return array(
            'module'     => 'One_Cms',
            'controller' => 'display',
            'action'     => 'page',
            'page-id'    => $this->_bo->getId(),
            'layout'     => 'cms.page'
            );
    }

    /**
     * Instantiates route based on passed Zend_Config structure
     *
     * @param Zend_Config $config Configuration object
     */
    public static function getInstance(Zend_Config $config)
    {
        if (isset($config->app)) {
            $app = $config->app;
        } else {
            $app = One::app();
        }
        return new self($config, null, $app);
    }

    public function app()
    {
        return $this->_app;
    }
}