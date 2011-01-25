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
 *     - Neither the name of Grégory PLANCHAT nor the names of its
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
 * HTML head block
 *
 * @uses        One_Core_Object
 *
 * @access      public
 * @author      gplanchat
 * @category    Core
 * @package     One_Core
 * @subpackage  One_Core
 */
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

        $this->headTitle()->setSeparator(' - ');

        return parent::_construct($options);
    }

    public function addStylesheet($stylesheet, $media = 'all')
    {
        if (preg_match('#^https?://|^/#i', $stylesheet) === 0) {
            $stylesheet = $this->getStyleUrl($stylesheet);
        }
        $this->headLink()->appendStylesheet($stylesheet, $media);
    }
}