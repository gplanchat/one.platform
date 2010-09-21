<?php

class One_Cms_Model_Dal_Route
    extends Zend_Db_Table_Abstract
{
    protected $_metadata = array(
        'entity_id' => array(
            'SCHEMA_NAME'      => 'one',
            'TABLE_NAME'       => 'route',
            'COLUMN_NAME'      => 'entity_id',
            'COLUMN_POSITION'  => 0,
            'DATA_TYPE'        => 'int',
            'DEFAULT'          => null,
            'NULLABLE'         => false,
            'LENGTH'           => 11,
            'SCALE'            => null,
            'PRECISION'        => null,
            'UNSIGNED'         => true,
            'PRIMARY'          => true,
            'PRIMARY_POSITION' => 1,
            'IDENTITY'         => true
            ),
        'path' => array(
            'SCHEMA_NAME'      => 'one',
            'TABLE_NAME'       => 'route',
            'COLUMN_NAME'      => 'path',
            'COLUMN_POSITION'  => 1,
            'DATA_TYPE'        => 'varchar',
            'DEFAULT'          => '',
            'NULLABLE'         => false,
            'LENGTH'           => 255,
            'SCALE'            => null,
            'PRECISION'        => null,
            'UNSIGNED'         => null,
            'PRIMARY'          => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY'         => false
            ),
        'parent_entity_id' => array(
            'SCHEMA_NAME'      => 'one',
            'TABLE_NAME'       => 'route',
            'COLUMN_NAME'      => 'parent_entity_id',
            'COLUMN_POSITION'  => 2,
            'DATA_TYPE'        => 'int',
            'DEFAULT'          => null,
            'NULLABLE'         => true,
            'LENGTH'           => null,
            'SCALE'            => null,
            'PRECISION'        => null,
            'UNSIGNED'         => false,
            'PRIMARY'          => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY'         => false
            ),
        'page_entity_id' => array(
            'SCHEMA_NAME'      => 'one',
            'TABLE_NAME'       => 'route',
            'COLUMN_NAME'      => 'page_entity_id',
            'COLUMN_POSITION'  => 3,
            'DATA_TYPE'        => 'int',
            'DEFAULT'          => null,
            'NULLABLE'         => false,
            'LENGTH'           => null,
            'SCALE'            => null,
            'PRECISION'        => null,
            'UNSIGNED'         => null,
            'PRIMARY'          => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY'         => false
            )
        );

    protected $_primary = 'entity_id';

    public function __construct()
    {
        $config = array(
            self::ADAPTER => new Zend_Test_DbAdapter()
            );

        parent::__construct($config);
    }

    public function find()
    {
        $ids = func_get_args();

        $rows = array();
        foreach ($ids as $id) {
            if (!isset($this->_routes[$id])) {
                continue;
            }
            $rows[] = $this->_routes[$id];
        }

        $data  = array(
            'table'    => $this,
            'data'     => $rows,
            'readOnly' => true,
            'rowClass' => $this->getRowClass(),
            'stored'   => true
        );

        $rowsetClass = $this->getRowsetClass();
        if (!class_exists($rowsetClass)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($rowsetClass);
        }

        return new $rowsetClass($data);
    }
}