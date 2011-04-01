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
    private $_session = null;

    public function preDispatch()
    {
        $this->_session = $this->app()->getModel('core/session');
    }

    public function indexAction()
    {
        $this->loadLayout('setup.home');

        $this->renderLayout();
    }

    protected function _git()
    {
        static $git = null;
        if ($git === null) {
            $git = $this->app()->getModel('core/command', array(
                'command' => 'git'
                ));
        }
        return $git;
    }

    protected function _getBranches($repository)
    {
        $options = array('HEAD');
        $list = $this->_git()->{'ls-remote'}($repository);

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

        return $options;
    }

    public function gitAction()
    {
        $this->loadLayout('setup.git');

        $repository = $this->getLayout()
            ->getBlock('form')
            ->getSubForm('git-repository')
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

    public function gitAjaxAction()
    {
        $repository = $this->_getParam('repository');

        $this->_session->setRepositoryUrl($repository);

        try {
            $data = $this->_getBranches($repository);
        } catch (One_Core_Exception_CommandError $e) {
            $data = array();
        }

        $this->getResponse()
            ->setBody(Zend_Json::encode($data))
            ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
        ;
    }

    public function gitPostAction()
    {
        $datas = $this->_getParam('gitrepository');

        try {
            if (realpath(dirname($datas['destination'])) === false) {
                $this->app()->throwException('core/invalid-method-call', 'Destination path does not exist.');
            } else if (realpath($datas['destination']) === false) {
                $this->_git()->clone($datas['repository'], $datas['destination']);

                $this->_git()
                    ->setWorkingDirectory($datas['destination'])
                    ->checkout($datas['branch'])
                ;
            } else {
                $this->_git()
                    ->setWorkingDirectory($datas['destination'])
                    ->checkout($datas['branch'])
                ;

                $this->_git()->pull($datas['repository']);
            }

            $this->_session->setRepositoryUrl($datas['repository']);
        } catch (One_Core_Exception_CommandError $e) {
            $this->_session->addError($e->getMessage());
            $this->_redirectError('git');
        }
        $this->_session->setStageOnePassed(true);

        $baseUrl = $this->getFrontController()->getBaseUrl();
        $this->getResponse()
            ->setRedirect($baseUrl . '/git', 302);
    }

    public function setupAction()
    {
        $this->loadLayout('setup.setup');

        $this->renderLayout();
    }

    protected function _getDatabaseInfo($engine, $connectionConfig)
    {
        $adapterList = array(
            'mysqli' => array(
                'adapter'=> 'core/connection.adapter.mysqli',
                'dialect' => 'mysql5'
                ),
            'pdo-mysql' => array(
                'adapter' => 'core/connection.adapter.pdo.mysql',
                'dialect' => 'mysql5'
                ),
//            'postgre' => array(
//                'adapter' => 'core/connection.adapter.postgre',
//                'dialect' => 'postgre9'
//                ),
//            'pdo-postgre' => array(
//                'adapter' => 'core/connection.adapter.pdo.postgre',
//                'dialect' => 'postgre9'
//                )
            );

        if (isset($adapterList[$engine])) {
            $adapter = $adapterList[$engine]['adapter'];
            $dialect = $adapterList[$engine]['dialect'];
        } else {
            $adapter = 'core/connection.adapter.mysqli';
            $dialect = 'mysql5';
        }

        $connection = $this->app()
            ->getResource($adapter, 'dal/database', $connectionConfig, $this->app())
        ;

        switch ($dialect) {
        case 'mysql5':
            return array(
                'version' => $connection->query('SELECT @@VERSION')->fetchColumn(0),
                'adapter' => $adapter,
                'dialect' => $dialect
                );
            break;

        case 'postgre':
            return array(
                'version' =>$connection->query('SELECT VERSION()')->fetchColumn(0),
                'adapter' => $adapter,
                'dialect' => $dialect
                );
            break;
        }
        return false;
    }

    protected function _getDatabaseVersion($engine, $connectionConfig)
    {
        $info = $this->_getDatabaseInfo($engine, $connectionConfig);

        if ($info === false) {
            return false;
        }
        return $info['version'];
    }

    public function setupRdbmsTestAjaxAction()
    {
        $connectionConfig = $this->_getParam('setuprdbms');
        if (isset($connectionConfig['engine'])) {
            $engine = $connectionConfig['engine'];
            unset($connectionConfig['engine']);
        } else {
            $engine = 'mysqli';
        }

        try {
            $return = array(
                'status'  => true,
                'version' => $this->_getDatabaseVersion($engine, $connectionConfig)
                );
        } catch (Zend_Db_Exception $e) {
            $return = array(
                'status' => false,
                'error'  => $e->getMessage()
                );
        }

        $this->getResponse()
            ->setBody(Zend_Json::encode($return))
            ->setHeader('Content-Type', 'application/json; encoding=UTF-8')
        ;
    }

    public function setupPostAction()
    {
        $connectionConfig = $this->_getParam('setuprdbms');
        if (isset($connectionConfig['engine'])) {
            $engine = $connectionConfig['engine'];
            unset($connectionConfig['engine']);
        } else {
            $engine = 'mysqli';
        }

        try {
            $info = $this->_getDatabaseInfo($engine, $connectionConfig);
            if ($info === false || !is_array($info) || !isset($info['dialect'])) {
                $this->app()->throwException('core/database.connectio-error',
                    'Could not connect to the specified database.');
            }

            $dialect = $info['dialect'];
            $adapter = $info['adapter'];
        } catch (One_Core_Exception $e) {
            $this->_session->addError($e->getMessage());
            $this->_redirectError('stage-one');
            return;
        }

        $prefixConfig = $this->_getParam('setupdatabase');
        if (isset($prefixConfig['prefix'])) {
            $prefix = $prefixConfig['prefix'];
        }

        $this->_session->setDatabaseEngine($engine);
        $this->_session->setDatabaseAdapter($adapter);
        $this->_session->setDatabaseDialect($dialect);
        $this->_session->setDatabaseConfig($connectionConfig);
        $this->_session->setDatabaseTablePrefix($prefix);

        $this->_session->setRegistrationData($this->_getParam('registration'));

        $config = simplexml_load_file(APPLICATION_PATH . DS. 'configs' . DS . 'local.xml.sample');

        $config->default->system->hostname = $_SERVER['HTTP_HOST'];
        $config->default->system->{'base-url'} = dirname($this->getFrontController()->getBaseUrl()) . '/';
        $config->default->system->{'style-url'} = dirname($this->getFrontController()->getBaseUrl()) . '/design/';
        $config->default->system->{'script-url'} = dirname($this->getFrontController()->getBaseUrl()) . '/js/';

        $config->backoffice->system->hostname = $_SERVER['HTTP_HOST'];
        $config->backoffice->system->{'base-url'} = dirname($this->getFrontController()->getBaseUrl()) . '/admin.php/';

        $connections = $config->default->general->database->connection;
        foreach (array('core_setup', 'core_read', 'core_write') as $connection) {
            $connections->$connection->engine = $adapter;
            $connections->$connection->dialect = $dialect;
            $tablePrefix = 'table-prefix';
            $connections->$connection->params->$tablePrefix = $prefixConfig;
            $connections->$connection->params->host = $connectionConfig['host'];
            $connections->$connection->params->username = $connectionConfig['username'];
            if (!empty($connectionConfig['password'])) {
                $connections->$connection->params->password = $connectionConfig['password'];
            } else {
                $connections->$connection->params->password = null;
            }
            $connections->$connection->params->dbname = $connectionConfig['dbname'];
            $connections->$connection->params->profiler = 0;
            $connections->$connection->params->charset = 'UTF8';
        }

        $config->asXml(APPLICATION_PATH . DS. 'configs' . DS . 'local.xml');

        $path = dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR;
        $baseUrl = dirname($this->getFrontController()->getBaseUrl());

        $baseUrl = str_replace('\\', '/', $baseUrl);
        if (substr($baseUrl, -1, 1) !== '/') {
            $baseUrl .= '/';
        }

        $htaccess =<<<HTACCESS_EOF
RewriteEngine On

RewriteBase {$baseUrl}

RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule . - [L,NC]

RewriteRule . index.php [L,NC]

SetEnv APPLICATION_ENV production
HTACCESS_EOF;
        file_put_contents($path . '.htaccess', $htaccess);
        copy($path . 'index.php.sample', $path . 'index.php');

        $baseUrl = $this->getFrontController()->getBaseUrl();
        $this->getResponse()
            ->setRedirect($baseUrl . '/setup-database', 302);
    }

    public function setupDatabaseAction()
    {
        $updater = $this->app()->getModel('setup/updater');

        try {
            $updater
                ->setup('One_Core', $this->_session->getDatabaseDialect())
                ->setup('One_User', $this->_session->getDatabaseDialect())
            ;

            $group = $this->app()
                ->getModel('user/group')
                ->setWebsiteId(1)
                ->setLabel('Super Administrators')
                ->save()
            ;

            $registrationData = $this->_session->getRegistrationData();
            $this->app()
                ->getModel('user/entity')
                ->setPrimaryGroupId($group->getId())
                ->register($registrationData, 1)
            ;
        } catch (Exception $e) {
            echo $e->getMessage();
            return;
        }

        $baseUrl = $this->getFrontController()->getBaseUrl();
        $this->getResponse()
            ->setRedirect($baseUrl . '/updates', 302);
    }

    public function updatesAction()
    {
        $updater = $this->app()->getModel('setup/updater');

        $modules = array();
        foreach ($updater->getApplicationModuleCollection('frontoffice') as $module) {
            $modules[$module->getData('identifier')] = array(
                'latest'    => $module->getVersion(),
                'installed' => '0.0.0'
                );
        }

        foreach ($updater->getApplicationModuleCollection('backoffice') as $module) {
            $modules[$module->getData('identifier')] = array(
                'latest'    => $module->getVersion(),
                'installed' => '0.0.0'
                );
        }

        foreach ($updater->getInstalledModuleCollection() as $module) {
            if (isset($modules[$module->getIdentifier()])) {
                $modules[$module->getIdentifier()]['installed'] = $module->getVersion();
            }
        }

        $this->loadLayout('setup.updates')
            ->getBlock('status')
            ->setModules($modules)
        ;

        $this->renderLayout();
    }

    public function installModuleAction()
    {
        $module = $this->getRequest()->getQuery('module');

        $updater = $this->app()->getModel('setup/updater');
        try {
            $config = $this->app()
                ->getSingleton('core/database.connection.pool')
                ->getConfig('core_setup')
            ;

            $updater->setup($module, $config['dialect']);
        } catch (Exception $e) {
            echo $e->getMessage();
            return;
        }

        $baseUrl = $this->getFrontController()->getBaseUrl();
        $this->getResponse()
            ->setRedirect($baseUrl . '/updates', 302);
    }

    public function jumpAction()
    {
        $baseUrl = dirname($this->getFrontController()->getBaseUrl());
        $this->getResponse()
            ->setRedirect($baseUrl, 302);
    }

    public function applyPatchAction()
    {
        $this->loadLayout('setup.apply-patch');

        $this->renderLayout();
    }

    public function applyPatchPostAction()
    {
        $url = $this->app()->getRouter()->assemble(array(
            'action' => 'apply-patch'
            ), 'setup');
        $this->_redirect($url);
    }
}