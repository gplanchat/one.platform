<?php

class One_User_Block_Login
    extends One_Core_Block_Html_Form
{
    protected $_submitLabel = 'Log in';

    public function _construct($options)
    {
        if (!isset($options['form'])) {
            $options['form'] = 'login';
        }

        $options = parent::_construct($options);

        $baseUrl = $this->app()->getFrontController()->getBaseUrl();

        $this->headScript()
            ->appendFile($baseUrl . '/js/jquery.js')
            ->appendFile($baseUrl . '/js/core.js')
            ->appendFile($baseUrl . '/js/security.js')
            ->appendFile($baseUrl . '/js/auth.js')
        ;

        $script =<<<SCRIPT_EOF
$(document).ready(function(){
    $.one.User.login($('#{$this->_form->getName()}'));
  });
SCRIPT_EOF;

        $this->headScript()
            ->appendScript($script)
        ;

        $passwordElement = $this->_form->getSubForm('login')->getElement('password');

        $class = $passwordElement->getAttrib('class');
        if (empty($class)) {
            $passwordElement->setAttrib('class', 'hidden');
        } else {
            $passwordElement->setAttrib('class', trim($class) . ' hidden');
        }
        $passwordElement->setAttrib('disabled', 'disabled');

        $this->_form->addElement('hidden', 'load_identity', array(
            'value' => $this->app()->getRouter()->assemble(array(
                'controller' => 'account',
                'action'     => 'login-ajax'
                )),
            'decorators' => array(
                'element' => array(
                    'decorator' => 'ViewHelper',
                    'params'    => 'FormHidden'
                    )
                )
            ));

        $this->_form->setAction($this->app()->getRouter()->assemble(array(
            'controller' => 'account',
            'action'     => 'login-ajax-post'
            )));

        return $options;
    }
}