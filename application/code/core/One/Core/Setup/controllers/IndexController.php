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
 * Base index controller
 *
 * @uses        One_Core_ControllerAbstract
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
class One_Core_Setup_IndexController
    extends One_Core_ControllerAbstract
{
    private $_git = null;
    private $_session = null;

    public function indexAction()
    {
        $this->_forward('stage-one');
    }

    public function preDispatch()
    {
        $this->_git = $this->app()->getModel('core/command', array(
            'command' => 'git'
            ));

        $this->_session = $this->app()->gteModel('core/session');
    }

    public function stageOneAction()
    {
        $this->loadLayout('setup.stage-one');

        $repository = $this->getLayout()
            ->getBlock('form')
            ->getSubForm('stage-one-repository')
        ;
        if (($url = $this->_session->getRepositoryUrl()) === null) {
            $url = $repository->getElement('repository')->getValue();
        }
        $branch = $repository->getElement('branch');
        $branch->addMultiOptions($this->_getBranches($url));

        if (strpos(PHP_OS, 'WIN') !== false) {
            $destination = $repository->getElement('destination');
            $destination->setValue(dirname($_SERVER['DOCUMENT_ROOT']));
        }

        $this->renderLayout();
    }

    public function stageOneAjaxAction()
    {
        $repository = $this->_getParam('repository');

        $this->_session->setRepositoryUrl($repository);

        $this->getResponse()
            ->setBody(Zend_Json::encode($this->_getBranches($repository)))
            ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
        ;
    }

    protected function _getBranches($repository)
    {
        $options = array('HEAD');
        try {
            $list = $this->_git->{'ls-remote'}($repository);

            $tags = array();
            $branches = array();
            foreach (explode("\n", $list) as $item) {
                if (!preg_match('#^([0-9A-F]{32,64})\s(?:refs/(?:(head|tag)s)/([^\s\^]+)|(HEAD)]*)$#i', $item, $matches)) {
                    continue;
                }
                if ($matches[2] == 'tag') {
                    $tags[$matches[3]] = $matches[3];
                } else if ($matches[2] == 'head') {
                    $branches[$matches[3]] = $matches[3];
                }
            }
            if (!empty($branches)) {
                $options['Branches'] = $branches;
            }
            if (!empty($tags)) {
                $options['Tags'] = $tags;
            }
        } catch (One_Core_Exception_CommandError $e) {
            var_dump($e);
        }
        return $options;
    }

    public function stageOnePostAction()
    {
        $datas = $this->_getParam('stageonerepository');

        try {
            if (realpath(dirname($datas['destination'])) === false) {
                $this->app()->throwException('core/invalid-method-call', 'Destination path does not exist.');
            } else if (realpath($datas['destination']) === false) {
                $this->_git->clone($datas['repository'], $datas['destination']);

                $this->_git
                    ->setWorkingDirectory($datas['destination'])
                    ->checkout($datas['branch'])
                ;
            } else {
                $this->_git
                    ->setWorkingDirectory($datas['destination'])
                    ->checkout($datas['branch'])
                ;

                $this->_git->pull($datas['repository']);
            }

            $this->_session->setRepositoryUrl($datas['repository']);
        } catch (One_Core_Exception_CommandError $e) {
            var_dump($e);
        }

        $url = $this->app()->getRouter()->assemble(array(
            'action' => 'stage-two'
            ), 'setup');
        $this->_redirect($url);
    }

    public function stageTwoAction()
    {
        $this->loadLayout('setup.stage-two');

        $this->renderLayout();
    }

    public function stageTwoPostAction()
    {
        $url = $this->app()->getRouter()->assemble(array(
            'action' => 'stage-three'
            ), 'setup');
        $this->_redirect($url);
    }

    public function stageThreeAction()
    {
        $this->loadLayout('setup.stage-three');

        $this->renderLayout();
    }

    public function stageThreePostAction()
    {
        $url = $this->app()->getRouter()->assemble(array(
            'action' => 'stage-four'
            ), 'setup');
        $this->_redirect($url);
    }

    public function stageFourAction()
    {
        $this->loadLayout('setup.stage-four');

        $this->renderLayout();
    }

    public function stageFourPostAction()
    {
        $this->_helper->redirector(array(
            'module'     => 'One_Core',
            'controller' => 'index',
            'action'     => 'index'
            ));
    }
}