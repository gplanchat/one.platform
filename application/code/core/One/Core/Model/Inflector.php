<?php

class One_Core_Model_Inflector
    implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $tmp = str_replace(' ', '', ucwords(str_replace('-', ' ', $value)));
        return str_replace(' ', '_', ucwords(str_replace('.', ' ', $tmp)));
    }
}