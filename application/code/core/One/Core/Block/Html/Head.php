<?php

class One_Core_Block_Html_Head
    extends One_Core_Block_Html
{
    protected function _construct($options)
    {
        if (isset($options['title'])) {
            $this->headTitle()->set($options['title']);
            unset($options['title']);
        } else {
            $this->headTitle()->set(null);
        }

        if (isset($options['doctype'])) {
            $this->setDoctype($options['doctype']);
            unset($options['doctype']);
        }

        if (isset($options['link'])) {
            $helper = $this->headLink();
            if (is_int(key($options['link']))) {
                foreach ($options['link'] as $link) {
                    $placement = strtoupper(isset($link['placement']) ? $link['placement'] : 'APPEND');
                    unset($link['placement']);
                    $helper->headLink($link, $placement);
                }
            } else {
                $placement = strtoupper(isset($link['placement']) ? $link['placement'] : 'APPEND');
                unset($options['link']['placement']);
                $helper->headLink($options['link'], $placement);
            }
            unset($options['link']);
            unset($helper);
        }

        if (isset($options['stylesheet'])) {
            $helper = $this->headStyle();
            if (is_int(key($options['stylesheet']))) {
                foreach ($options['stylesheet'] as $style) {
                    $href = isset($style['href']) ? $style['href'] : null;
                    $media = strtoupper(isset($style['media']) ? $style['media'] : 'all');
                    $helper->addStylesheet($href, $media);
                }
            } else {
                $href = isset($options['stylesheet']['href']) ? $options['stylesheet']['href'] : null;
                $media = strtoupper(isset($options['style']['media']) ? $options['style']['media'] : 'all');
                $helper->appendStylesheet($href, $media);
            }
            unset($options['style']);
            unset($helper);
        }

        if (isset($options['script'])) {
            $helper = $this->headScript();
            if (is_int(key($options['script']))) {
                foreach ($options['script'] as $script) {
                    $href = isset($script['href']) ? $script['href'] : null;
                    $type = isset($script['type']) ? $script['type'] : 'text/javascript';

                    $helper->setFile($href, $type);
                }
            } else {
                $href = isset($options['script']['href']) ? $options['script']['href'] : null;
                $type = isset($options['script']['type']) ? $options['script']['type'] : 'text/javascript';

                $helper->setFile($href, $type);
            }
            unset($options['script']);
            unset($helper);
        }

        if (isset($options['meta'])) {
            $helper = $this->headMeta();
            if (is_int(key($options['meta']))) {
                foreach ($options['meta'] as $meta) {
                    $content = isset($meta['content']) ? $meta['content'] : null;
                    $keyValue = isset($meta['key-value']) ? $meta['key-value'] : null;
                    $keyType = isset($meta['key-type']) ? $meta['key-type'] : null;
                    $modifiers = isset($meta['modifiers']) ? $meta['modifiers'] : array();
                    $placement = strtoupper(isset($meta['placement']) ? $meta['placement'] : Zend_View_Helper_Placeholder_Container_Abstract::APPEND);

                    $helper->headMeta($content, $keyValue, $keyType, $modifiers, $placement);
                }
            } else {
                $content = isset($options['meta']['content']) ? $options['meta']['content'] : null;
                $keyValue = isset($options['meta']['key-value']) ? $options['meta']['key-value'] : null;
                $keyType = isset($options['meta']['key-type']) ? $options['meta']['key-type'] : null;
                $modifiers = isset($options['meta']['modifiers']) ? $options['meta']['modifiers'] : array();
                $placement = strtoupper(isset($options['meta']['placement']) ? $options['meta']['placement'] : Zend_View_Helper_Placeholder_Container_Abstract::APPEND);

                $helper->headMeta($content, $keyValue, $keyType, $modifiers, $placement);
            }
            unset($options['meta']);
            unset($helper);
        }

        return parent::_construct($options);
    }

    public function addStylesheet($stylesheet, $media = 'all')
    {
        $this->headLink()->appendStylesheet($stylesheet, $media);
    }
}