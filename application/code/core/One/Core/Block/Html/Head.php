<?php

class One_Core_Block_Html_Head
    extends One_Core_Block_Html
{
    protected $_title = null;

    protected $_doctype = null;

    protected $_headLink = null;

    protected $_headScript = null;

    protected $_headStyle = null;

    protected $_headMeta = null;

    public function _construct($options = array())
    {
        if (isset($options['title'])) {
            $this->setTitle($options['title']);
            unset($options['title']);
        }

        if (isset($options['doctype'])) {
            $this->setDoctype($options['doctype']);
            unset($options['doctype']);
        }

        if (isset($options['link'])) {
            if (is_int(key($options['link']))) {
                foreach ($options['link'] as $link) {
                    $placement = strtoupper(isset($link['placement']) ? $link['placement'] : 'APPEND');
                    unset($link['placement']);
                    $this->setHeadLink($link, $placement);
                }
            } else {
                $placement = strtoupper(isset($link['placement']) ? $link['placement'] : 'APPEND');
                unset($options['link']['placement']);
                $this->setHeadLink($options['link'], $placement);
            }
            unset($options['link']);
        }

        if (isset($options['style'])) {
            if (is_int(key($options['style']))) {
                foreach ($options['style'] as $style) {
                    $content = isset($style['content']) ? $style['content'] : null;
                    $placement = strtoupper(isset($style['placement']) ? $style['placement'] : 'APPEND');
                    $attributes = isset($style['attributes']) ? $style['attributes'] : array();

                    $this->setHeadStyle($content, $placement, $attributes);
                }
            } else {
                $content = isset($options['style']['content']) ? $options['style']['content'] : null;
                $placement = strtoupper(isset($options['style']['placement']) ? $options['style']['placement'] : 'APPEND');
                $attributes = isset($options['style']['attributes']) ? $options['style']['attributes'] : array();
                $this->setHeadStyle($content, $placement, $attributes);
            }
            unset($options['style']);
        }

        if (isset($options['script'])) {
            if (is_int(key($options['script']))) {
                foreach ($options['script'] as $script) {
                    $mode = isset($script['mode']) ? $script['mode'] : Zend_View_Helper_HeadScript::FILE;
                    $spec = isset($script['spec']) ? $script['spec'] : null;
                    $placement = strtoupper(isset($script['placement']) ? $script['placement'] : 'APPEND');
                    $attributes = isset($script['attributes']) ? $script['attributes'] : array();
                    $type = isset($script['type']) ? $script['type'] : 'text/javascript';

                    $this->setHeadScript($content, $spec, $placement, $attributes, $type);
                }
            } else {
                $mode = isset($options['script']['mode']) ? $options['script']['mode'] : Zend_View_Helper_HeadScript::FILE;
                $spec = isset($options['script']['spec']) ? $options['script']['spec'] : null;
                $placement = strtoupper(isset($options['script']['placement']) ? $options['script']['placement'] : 'APPEND');
                $attributes = isset($options['script']['attributes']) ? $options['script']['attributes'] : array();
                $type = isset($options['script']['type']) ? $options['script']['type'] : 'text/javascript';

                $this->setHeadScript($content, $spec, $placement, $attributes, $type);
            }
            unset($options['script']);
        }

        if (isset($options['meta'])) {
            if (is_int(key($options['meta']))) {
                foreach ($options['meta'] as $meta) {
                    $content = isset($meta['content']) ? $meta['content'] : null;
                    $keyValue = isset($meta['key-value']) ? $meta['key-value'] : null;
                    $keyType = isset($meta['key-type']) ? $meta['key-type'] : null;
                    $modifiers = isset($meta['modifiers']) ? $meta['modifiers'] : array();
                    $placement = strtoupper(isset($meta['placement']) ? $meta['placement'] : Zend_View_Helper_Placeholder_Container_Abstract::APPEND);

                    $this->setHeadMeta($content, $keyValue, $keyType, $modifiers, $placement);
                }
            } else {
                $content = isset($options['meta']['content']) ? $options['meta']['content'] : null;
                $keyValue = isset($options['meta']['key-value']) ? $options['meta']['key-value'] : null;
                $keyType = isset($options['meta']['key-type']) ? $options['meta']['key-type'] : null;
                $modifiers = isset($options['meta']['modifiers']) ? $options['meta']['modifiers'] : array();
                $placement = strtoupper(isset($options['meta']['placement']) ? $options['meta']['placement'] : Zend_View_Helper_Placeholder_Container_Abstract::APPEND);

                $this->setHeadMeta($content, $keyValue, $keyType, $modifiers, $placement);
            }
            unset($options['meta']);
        }

        return parent::_construct($options);
    }

    public function setTitle($title, $setType = null)
    {
        if ($this->_title === null) {
            $this->_title = new Zend_View_Helper_HeadTitle();
        }
        $this->_title->headTitle($title, $setType);

        return $this;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setDoctype($doctype = null)
    {
        if (is_null($this->_doctype)) {
            $this->_doctype = new Zend_View_Helper_Doctype();
        }
        $this->_doctype->doctype($doctype);

        return $this;
    }

    public function getDoctype()
    {
        return $this->_doctype;
    }

    public function setHeadLink(array $attributes = null, $placement = Zend_View_Helper_Placeholder_Container_Abstract::APPEND)
    {
        if (is_null($this->_doctype)) {
            $this->_headLink = new Zend_View_Helper_HeadLink();
        }
        $this->_headLink->headLink($attributes, $placement);

        return $this;
    }

    public function getHeadLink()
    {
        return $this->_headLink;
    }

    public function setHeadScript($mode = Zend_View_Helper_HeadScript::FILE, $spec = null, $placement = 'APPEND', array $attrs = array(), $type = 'text/javascript')
    {
        if (is_null($this->_doctype)) {
            $this->_headScript = new Zend_View_Helper_HeadScript();
        }
        $this->_headScript->headScript($mode, $spec, $placement, $attrs, $type);

        return $this;
    }

    public function getHeadScript()
    {
        return $this->_headScript;
    }

    public function setHeadStyle($content = null, $placement = 'APPEND', $attributes = array())
    {
        if (is_null($this->_doctype)) {
            $this->_headStyle = new Zend_View_Helper_HeadStyle();
        }
        $this->_headStyle->headStyle($content, $placement, $attributes);

        return $this;
    }

    public function getHeadStyle()
    {
        return $this->_headStyle;
    }

    public function setHeadMeta($content = null, $keyValue = null, $keyType = 'name', $modifiers = array(), $placement = Zend_View_Helper_Placeholder_Container_Abstract::APPEND)
    {
        if (is_null($this->_doctype)) {
            $this->_headMeta = new Zend_View_Helper_HeadMeta();
        }
        $this->_headMeta->headMeta($content, $keyValue, $keytype, $modifiers, $placement);

        return $this;
    }

    public function getHeadMeta()
    {
        return $this->_headMeta;
    }
}