<?php

class One_Wiki_Model_Syntax_Textile
{
    protected $_map = array(
        'headings' => array(
            'search'  => '@^h(<level>[1-6])(?:\((?<class>[a-zA-Z][a-zA-Z0-9\-_]+[a-zA-Z])\))?(?:\[#(?<id>[a-zA-Z][a-zA-Z0-9\-_\.:]+[a-zA-Z])\])\.(.*)$@',
            'replace' => '<h$1>$2</h$1>'
            ),
        'underline' => array(
            'search'  => '@_(.*)_@U',
            'replace' => '<span class="underline">$1</span>'
            ),
        'bold' => array(
            'search'  => '#@(.*)@#U',
            'replace' => '<em class="bold">$1</em>'
            ),
        'italic' => array(
            'search'  => '@/(.*)/@U',
            'replace' => '<span class="italic">$1</span>'
            )
        );

    public function render($contents)
    {
        $references = array();

        foreach ($this->_map as $type => $mapData) {
            $contents = preg_replace_all($mapData['search'], $mapData['replace'], $contents);
        }

        return $contents;
    }
}