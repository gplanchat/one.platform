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
 * Administration base index controller
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Admin_Core
 * @subpackage  One_Admin_Core
 */
class One_Admin_Core_DashboardController
    extends One_Core_ControllerAbstract
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function websiteChildListAjaxAction()
    {
        try {
            $currentWebsiteId = $this->app()->getWebsiteId();
            $websiteId = $this->_getParam('website', $currentWebsiteId);
            $website = $this->app()
                ->getModel('core/website')
                ->load($websiteId)
            ;

            if ($website->getId() !== $currentWebsiteId && !$website->isChildOf($currentWebsiteId)) {
                $this->getResponse()
//                    ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
                    ->setBody(Zend_Json::encode(array()))
                ;
            }

            $collection = $this->app()
                ->getModel('core/website.collection')
                ->setRoot($currentWebsiteId)
                ->load()
            ;

            $this->getResponse()
                ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
                ->setBody(Zend_Json::encode($collection->toHash('label')))
            ;
        } catch (Zend_Db_Exception $e) {
            $this->getResponse()
                ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
                ->setBody(Zend_Json::encode('An error occured: ' . $e->getMessage()))
            ;
        }
    }

    public function groupListAjaxAction()
    {
        $webiste = $this->app()
            ->getModel('core/website')
            ->load($this->_getParam('website'));

        if (!$website->isChildOf($this->app()->getWebsiteId())) {
            $this->getResponse()
                ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
                ->setBody(Zend_Json::encode(array()))
            ;
        }

        $collection = $this->app()
            ->getModel('user/group.collection')
            ->addAttributeFilter('website_id', $website->getId());

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
            ->setBody(Zend_Json::encode($collection->load()->toArray()))
        ;
    }

    public function userListAjaxAction()
    {
        $collection = $this->app()
            ->getModel('cms/page.collection')
            ->setPage($this->_getParam('p'), $this->_getParam('n'));

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
            ->setBody(Zend_Json::encode($collection->load()->toArray()))
        ;
    }

    public function accessControlListAjaxAction()
    {
        $collection = $this->app()
            ->getModel('cms/page.collection')
            ->setPage($this->_getParam('p'), $this->_getParam('n'));

        $this->getResponse()
            ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
            ->setBody(Zend_Json::encode($collection->load()->toArray()))
        ;
    }
}
