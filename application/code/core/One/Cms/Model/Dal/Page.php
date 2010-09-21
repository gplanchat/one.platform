<?php

class One_Cms_Model_Dal_Page
    extends Zend_Db_Table_Abstract
{
    private $_routes =array(
        2 => array(
            'entity_id' => 2,
            'title'     => 'Home',
            'content'   => 'Lorem Ipsum Home Page'
            ),
        28 => array(
            'entity_id' => 28,
            'title'     => 'Cookies',
            'content'   => 'Lorem Ipsum Cookies'
            ),
        16 => array(
            'entity_id' => 16,
            'title'     => 'About',
            'content'   => 'Lorem Ipsum About'
            ),
        30 => array(
            'entity_id' => 30,
            'title'     => 'Rules',
            'content'   => 'Lorem Ipsum Rules'
            ),
        45 => array(
            'entity_id' => 45,
            'title'     => 'More',
            'content'   => 'Lorem Ipsum More'
            )
        );


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
        'title' => array(
            'SCHEMA_NAME'      => 'one',
            'TABLE_NAME'       => 'route',
            'COLUMN_NAME'      => 'title',
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
        'content' => array(
            'SCHEMA_NAME'      => 'one',
            'TABLE_NAME'       => 'route',
            'COLUMN_NAME'      => 'content',
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
            )
        );

    protected $_primary = 'entity_id';

    public function __construct($config = array())
    {
        if (!isset($config[self::ADAPTER])) {
            $config[self::ADAPTER] = Zend_Db::factory('Pdo_Sqlite', array(
                'dbname' => APPLICATION_PATH . DS . 'datas' . DS . 'foo.db'
                ));
        }
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