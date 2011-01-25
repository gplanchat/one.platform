<?php

$this->setSetupConnection('user_setup');

$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('user/entity')}
    ADD COLUMN `locale` VARCHAR(10) NOT NULL
    AFTER `primary_group_id`
SQL_EOF;

$this->query($sql);

