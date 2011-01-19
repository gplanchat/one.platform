<?php

$this->setSetupConnection('core_setup');

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('core/config')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('setup/module')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('core/website')};
SQL_EOF;

$this->query($sql);
