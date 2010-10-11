<?php


abstract class One_Core_BlockAbstract
    extends One_Core_Object
    implements Zend_View_Interface
{
    protected $_name = '';

    protected $_childNodes = array();

    protected $_childIndex = array();

    protected $_basePath = '';

    protected $_scriptPath = '';

    public function _construct($options)
    {
        $config = One::app()->getOption('frontoffice');
        $basePath = implode(One::DS, array(APPLICATION_PATH, 'design', 'frontoffice',
            $config['layout']['design'], $config['layout']['template']));

        $this->setBasePath($basePath)
            ->setScriptPath($basePath . One::DS . 'template')
        ;

        if (isset($options['name'])) {
            $this->setName($options['name']);
            unset($options['name']);
        } else {
            $this->setName(uniqid('block_'));
        }

        if (isset($options['block'])) {
            if (is_int(key($options['block']))) {
                foreach ($options['block'] as $block) {
                    $this->_buildChildNode($block);
                }
            } else {
                $this->_buildChildNode($options['block']);
            }
            unset($options['block']);
        }

        if (isset($options['action'])) {
            if (is_int(key($options['action']))) {
                foreach ($options['action'] as $action) {
                    $this->_executeAction($action);
                }
            } else {
                $this->_executeAction($options['action']);
            }
            unset($options['action']);
        }

        return parent::_construct($options);
    }

    protected function _buildChildNode($node)
    {
        $childBlock = One::app()
            ->getBlock($node['type'], $node)
            ->setBasePath($this->getBasePath())
            ->setScriptPath($this->getScriptPath())
        ;

        if (isset($node['name'])) {
            $name = $node['name'];
        } else {
            $name = uniqid('block_');
        }
        $this->appendChild($name, $childBlock);

        return $this;
    }

    protected function _executeAction($action)
    {
        $reflectionObject = new ReflectionObject($this);
        if ($reflectionObject->hasMethod($action['method'])) {
            $reflectionMethod = $reflectionObject->getMethod($action['method']);

            $callParams = array_pad(array(), $reflectionMethod->getNumberOfParameters(), null);
            $parameters = array();
            foreach ($reflectionMethod->getParameters() as $reflectionParam) {
                $parameters[$reflectionParam->getName()] = $reflectionParam;
                if ($reflectionParam->isOptional()) {
                    $callParams[$reflectionParam->getPosition()] = $reflectionParam->getDefaultValue();
                }
            }
            foreach ($action['params'] as $paramName => $paramValue) {
                if (!isset($parameters[$paramName])) {
                    continue;
                }

                if (isset($paramValue)) {
                    $callParams[$parameters[$paramName]->getPosition()] = $paramValue;
                } else {
                    $callParams[$parameters[$paramName]->getPosition()] = null;
                }
            }

            $reflectionMethod->invokeArgs($this, $callParams);
        }

        return $this;
    }

    public function getChildNode($childName)
    {
        if (isset($this->_childNodes[$childName])) {
            return $this->_childNodes[$childName];
        }
        return null;
    }

    public function getAllChildNodes()
    {
        return $this->_childNodes;
    }

    public function renderChild($childName, $templateName = null)
    {
        return $this->getChildNode($childName)->render($templateName);
    }

    public function appendChild($childName, $childNode)
    {
        $this->_childNodes[$childName] = $childNode;
        $this->_childIndex[] = $childName;

        return $this;
    }

    public function prependChild($childName, $childNode)
    {
        $this->_childNodes[$childName] = $childNode;
        array_unshift($this->_childIndex, $childName);

        return $this;
    }

    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function render($name = null)
    {
        $obLevel = ob_get_level();

        $content = $this->_render();

        while (ob_get_level() < $obLevel) {
            ob_end_clean();
        }

        return $content;
    }

    public function getEngine()
    {
    }

    public function setScriptPath($path)
    {
        $this->_scriptPath = $path;

        return $this;
    }

    public function getScriptPath()
    {
        return $this->_scriptPath;
    }

    public function getScriptPaths()
    {
        return $this->_scriptPath;
    }

    public function setBasePath($path, $classPrefix = 'Zend_View')
    {
        $this->_basePath = $path;

        return $this;
    }

    public function getBasePath()
    {
        return $this->_basePath;
    }

    public function addBasePath($path, $classPrefix = 'Zend_View')
    {
        $this->setBasePath($path, $classPrefix);

        return $this;
    }

    public function assign($spec, $value = null)
    {
    }

    public function clearVars()
    {
    }
}