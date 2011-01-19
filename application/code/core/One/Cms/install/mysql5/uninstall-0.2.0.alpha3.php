<?php

$this->setSetupConnection('cms_setup');

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('cms/page')};
SQL_EOF;

$this->query($sql);