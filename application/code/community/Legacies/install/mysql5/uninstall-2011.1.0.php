<?php

$this->setSetupConnection('legacies_setup');

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/aks')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/alliance')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/annonce')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/banned')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/buddy')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/chat')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/config')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/declared')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/errors')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/fleets')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/galaxy')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/iraks')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/lunas')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/messages')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/multi')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/notes')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/planets')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/rw')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/statpoints')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/users')};
SQL_EOF;

$this->query($sql);
