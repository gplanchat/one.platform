<?php

class One_Cms_Model_Template
    extends One_Core_Object
{
    const NS = 'http://1platform.org/cms/1.0/';

    public function render(One_Core_Model_Layout $layout, $content, $vars = array())
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXml('<cms xmlns:cms="' . self::NS . '">' . $content . '</cms>');

        $blockList = $dom->getElementsByTagNameNS(self::NS, 'block');
        if ($blockList instanceof DOMNodeList) {
            foreach ($blockList as $block) {
                if (!$block->hasAttribute('type')) {
                    continue;
                }

                $data = array();
                foreach ($block->attributes as $attributeName => $value) {
                    if ($attributeName === 'type') {
                        continue;
                    }
                    $data[$attributeName] = $value->value;
                }

                $result = $this->app()
                    ->getBlock($block->getAttribute('type'), $data, $layout)
                    ->render(null)
                ;
                $replace = $dom->createTextNode($result);

                $block->parentNode->replaceChild($replace, $block);
            }
        }

        $varList = $dom->getElementsByTagNameNS(self::NS, 'var');
        if ($varList instanceof DOMNodeList) {
            foreach ($varList as $var) {
                if (!$var->hasAttribute('name')) {
                    continue;
                }

                if (!isset($vars[$var->getAttribute('name')])) {
                    continue;
                }

                $replace = $dom->createTextNode($vars[$var->getAttribute('name')]);

                $var->parentNode->replaceChild($replace, $var);
            }
        }

        $rendering = '';
        foreach ($dom->documentElement->childNodes as $node) {
            $rendering .= $dom->saveXML($node);
        }
        return $rendering;
    }
}