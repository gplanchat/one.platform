<?php

class One_Core_Model_Application
    extends Zend_Application
{
    protected $_websiteId = null;

    protected $_activeModules = array();

    protected $_dependencies = array();

    const DS = DIRECTORY_SEPARATOR;
    const PS = PATH_SEPARATOR;

    const DEFAULT_CONFIG_SECTION = 'default';

    public function __construct($websiteId, $environment)
    {
        $this->_websiteId = $websiteId;

        $configFile = implode(self::DS, array(APPLICATION_PATH, 'configs', 'application.xml'));
        require_once 'Zend/Config/Xml.php';
        $config = new Zend_Config_Xml($configFile, self::DEFAULT_CONFIG_SECTION, true);
        try {
            $config->merge(new Zend_Config_Xml($configFile, $environment, true));
        } catch (Zend_Config_Exception $e) {
        }

        $configFile = implode(self::DS, array(APPLICATION_PATH, 'configs', 'local.xml'));
        require_once 'Zend/Config/Xml.php';
        $config->merge(new Zend_Config_Xml($configFile, self::DEFAULT_CONFIG_SECTION, true));
        try {
            $config->merge(new Zend_Config_Xml($configFile, $environment, true));
        } catch (Zend_Config_Exception $e) {
        }

        $pathPattern = implode(self::DS, array(APPLICATION_PATH, 'code', '%s', '%s', 'configs', 'module.xml'));
        if (($modules = $config->get('modules')) === null) {
            require_once 'One/core/Exception/ConfigurationError.php';
            throw new One_Core_Exception_ConfigurationError(
                "No modules found. A core module should at least be declared.");
        }
        foreach ($modules as $moduleName => $moduleConfig) {
            if (!in_array(strtolower($moduleConfig->get('active')), array(1, true, '1', 'true', 'on'))) {
                continue;
            }

            if (($codePool = $moduleConfig->get('codePool')) === null) {
                $codePool = 'local';
                $moduleConfig->codePool = $codePool;
            }

            $path = sprintf($pathPattern, $codePool, str_replace('_', DS, $moduleName));
            if (!file_exists($path)) {
                $modules->get($moduleName)->active = false;
                continue;
            }

            $moduleConfig = new Zend_Config_Xml($path, null, true);
            $config->merge($moduleConfig);

            $this->_addModule($moduleName, $moduleConfig);
        }

        $this->_validateModulesActivation($modules);

        $config->setReadOnly();

        parent::__construct($environment, $config);
    }

    protected function _validateModulesActivation($modulesConfig)
    {
        $moduleDependencies = $this->_dependencies;
        $sortedModules = array();
        while (!empty($moduleDependencies)) {
            $modulesCount = count($moduleDependencies);
            foreach ($moduleDependencies as $slaveModule => $dependencies) {
                if (!empty($dependencies)) {
                    continue;
                }
                unset($moduleDependencies[$slaveModule]);
                $sortedModules[$slaveModule] = null;

                foreach ($moduleDependencies as $module => $dependencies) {
                    foreach ($dependencies as $masterModule) {
                        if (($offset = array_search($slaveModule, $moduleDependencies[$module])) !== false) {
                            unset($moduleDependencies[$module][$offset]);
                        }
                    }
                }
            }
            if ($modulesCount === count($moduleDependencies)) {
//                throw new One_Core_Exception_ConfigurationError(
//                    'Module topological sort could not be proceeded, the dependency tree may be cyclic');
                break;
            }
        }

        $this->_activeModules = array_keys($sortedModules);
    }

    protected function _addModule($moduleName, $moduleConfig)
    {
        if (($dependencies = $moduleConfig->get('requires')) !== null) {
            foreach ($dependencies as $dependency) {
                if (!isset($this->_dependencies[$moduleName])) {
                    $this->_dependencies[$moduleName] = array();
                }
                $this->_dependencies[$moduleName][] = $dependency;
            }
        } else {
            $this->_dependencies[$moduleName] = array();
        }
    }

    public function getWebsiteId()
    {
        return $this->_websiteId;
    }
}