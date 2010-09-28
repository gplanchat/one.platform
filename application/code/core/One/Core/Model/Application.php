<?php

class One_Core_Model_Application
    extends Zend_Application
{
    protected $_websiteId = null;

    protected $_activeModules = array();

    protected $_dependencies = array();

    const DS = DIRECTORY_SEPARATOR;
    const PS = PATH_SEPARATOR;

    public function __construct($websiteId, $environment, Zend_Config $globalConfig = null)
    {
        $this->_websiteId = $websiteId;

        if ($globalConfig === null) {
            $configFile = implode(self::DS, array(APPLICATION_PATH,
                'configs', 'application.xml'));

            require_once 'Zend/Config/Xml.php';
            $config = new Zend_Config_Xml($configFile, $environment, true);
        } else if (($environmentConfig = $globalConfig->get($environment)) !== null) {
            $configArray = $environmentConfig->toArray();
            $config = new Zend_Config($configArray, true);

            if (isset($configArray['extends'])) {
                $sectionName = $configArray['extends'];
                while ($sectionName !== null) {
                    if (!isset($globalConfig->{$sectionName})) {
                        require_once 'Zend/Config/Exception.php';
                        throw new Zend_Config_Exception("Section '$sectionName' cannot be found in $xml");
                    }
                    $tmp = new Zend_Config($globalConfig->{$sectionName}->toArray(), true);
                    $config = $tmp->merge($config);
                    $sectionName = $globalConfig->{$sectionName}->extends;
                }
            }

            unset($config->extends);
        } else {
            throw new One_Core_Exception_ConfigurationError(
                "Environement '{$environment}' not found.");
        }

        $pathPattern = implode(self::DS, array(APPLICATION_PATH,
            'code', '%s', '%s', 'configs', 'module.xml'));

        if (($modules = $config->get('modules')) === null) {
            throw new One_Core_Exception_ConfigurationError(
                "No modules found. A core module should at least be declared.");
        }

        foreach ($modules as $moduleName => $moduleConfig) {
            $this->_addModule($config, $moduleName, $moduleConfig, $pathPattern);
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

    protected function _addModule($config, $moduleName, $moduleConfig, $pathPattern)
    {
        if (!in_array(strtolower($moduleConfig->get('active')), array(1, true, '1', 'true', 'on'))) {
            return;
        }

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

        if (($codePool = $moduleConfig->get('codePool')) === null) {
            $codePool = 'local';
            $moduleConfig->codePool = $codePool;
        }

        $path = sprintf($pathPattern, $codePool, str_replace('_', self::DS, $moduleName));
        if (!file_exists($path)) {
            $config->get('modules')->get($moduleName)->active = false;
            return;
        }

        $moduleConfig = new Zend_Config_Xml($path);
        if (($envConfig = $moduleConfig->get($environment)) !== null) {
            $config->merge($envConfig);
        } else if (($envConfig = $moduleConfig->get('default')) !== null) {
            $config->merge($envConfig);
        } else {
            $config->merge($moduleConfig);
        }
    }

    public function getWebsiteId()
    {
        return $this->_websiteId;
    }
}