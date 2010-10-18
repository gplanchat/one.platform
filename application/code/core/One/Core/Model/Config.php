<?php

class One_Core_Model_Config
    extends One_Core_Bo_EntityAbstract
{
    protected $_forms = null;
    protected $_fieldsets = null;

    protected function _construct($options)
    {
        $this->_init('core/config');

        return parent::_construct($options);
    }

    /**
     * TODO PHPDoc
     *
     * @param string $fieldsetName
     * @return Zend_Form
     */
    public function getFieldset($fieldsetName)
    {
        if ($this->_fieldsets === null) {
            $this->_fieldsets = $this->app()->getConfig('general.fieldsets');
        }
        if (!isset($this->_fieldsets[$fieldsetName])) {
            return null;
        }
        return new Zend_Form_SubForm($this->_fieldsets[$fieldsetName]);
    }

    /**
     * TODO PHPDoc
     *
     * @param string $fieldsetName
     * @return Zend_Form
     */
    public function getForm($formName)
    {
        if ($this->_forms === null) {
            $this->_forms = $this->app()->getConfig('general.forms');
        }
        if (!isset($this->_forms[$formName])) {
            return null;
        }
        if (isset($this->_forms[$formName]['params'])) {
            $form = new Zend_Form($this->_forms[$formName]['params']);
        } else {
            $form = new Zend_Form();
        }

        if (isset($this->_forms[$formName]['actions'])) {
            foreach ($this->_forms[$formName]['actions'] as $actionName => $actionUrl) {
                $params = isset($actionUrl['params']) ? $actionUrl['params'] : array();
                $name = isset($actionUrl['name']) ? $actionUrl['name'] : null;
                $reset = isset($actionUrl['reset']) ? $actionUrl['reset'] : false;

                switch ($actionName) {
                case 'submit':
                    $form->setAction($this->app()->getRouter()->assemble($params, $name, $reset));
                    break;

                case 'success':
                case 'error':
                    $form->addElement('hidden', $actionName, array(
                        'value' => $this->app()->getRouter()->assemble($params, $name, $reset),
                        'decorators' => array(
                            'element' => array(
                                'decorator' => 'ViewHelper',
                                'params'    => 'FormHidden'
                                )
                            )
                        ));
                    break;
                }
            }
        }

        if (isset($this->_forms[$formName]['fieldsets'])) {
            foreach ($this->_forms[$formName]['fieldsets'] as $fieldset => $name) {
                if ($fieldset === null) {
                    continue;
                }
                if (!is_string($name) || empty($name)) {
                    $name = $fieldset;
                }
                $form->addSubForm($this->getFieldset($fieldset), $name);
            }
        }

        return $form;
    }
}