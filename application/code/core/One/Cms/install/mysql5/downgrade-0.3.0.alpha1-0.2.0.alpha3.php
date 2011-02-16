<?php

$this->setSetupConnection('cms_setup');

$sql = <<<SQL_EOF
DROP TABLE IF EXISTS {$this->getTableName('cms/gaget')};
SQL_EOF;

$this->query($sql);
