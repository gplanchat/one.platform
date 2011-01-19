<?php

$this->setSetupConnection('user_setup');

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('user/authentication')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('user/group')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('user/entity')};
SQL_EOF;

$this->query($sql);
