<?php

class One_Core_Block_Text_Concat
    extends One_Core_BlockAbstract
{
    public function _render()
    {
        $render = '';

        foreach ($this->getAllChildNodes() as $child) {
            $render .= $child->render(null);
        }

        return $render;
    }
}