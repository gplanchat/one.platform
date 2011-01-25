<?php

$this->setSetupConnection('user_setup');

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('user/group')} (
   `group_id`           SMALLINT UNSIGNED   NOT NULL    AUTO_INCREMENT,
   `website_id`         TINYINT UNSIGNED     NOT NULL,
   `label`              VARCHAR(100)        NOT NULL,
   PRIMARY KEY (`group_id`),
   UNIQUE KEY (`website_id`,`label`),
   KEY `IDX_USER_GROUP__LABEL` (`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('user/group', 'user_read',  array('SELECT'))
    ->grant('user/group', 'user_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('user/group', 'user_setup')
;

$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('user/group')}
  ADD CONSTRAINT `FK_USER_GROUP_GROUP_ID__CORE_WEBSITE_ENTITY_ID`
    FOREIGN KEY (`website_id`)
    REFERENCES {$this->getTableName('core/website')} (`entity_id`)
      ON DELETE RESTRICT
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('user/entity')} (
   `entity_id`          SMALLINT UNSIGNED       NOT NULL    AUTO_INCREMENT,
   `website_id`         TINYINT UNSIGNED        NOT NULL,
   `username`           VARCHAR(24)             NOT NULL,
   `realname`           VARCHAR(50)             NOT NULL,
   `email`              VARCHAR(120)            NOT NULL,
   `primary_group_id`   SMALLINT UNSIGNED       NOT NULL,
   `created_at`         DATETIME                NOT NULL,
   `updated_at`         DATETIME                NOT NULL,
   PRIMARY KEY (`entity_id`),
   UNIQUE KEY (`email`, `website_id`),
   UNIQUE KEY (`username`, `website_id`),
   KEY `IDX_USER_ENTITY__WEBSITE_ID` (`website_id`),
   KEY `IDX_USER_ENTITY__RELANAME` (`realname`,`website_id`),
   KEY `IDX_USER_ENTITY__PRIMARY_GROUP_ID` (`primary_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('user/entity', 'user_read',  array('SELECT'))
    ->grant('user/entity', 'user_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('user/entity', 'user_setup')
;

$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('user/entity')}
  ADD CONSTRAINT `FK_USER_ENTITY_WEBSITE_ID__CORE_WEBSITE_ENTITY_ID`
    FOREIGN KEY (`website_id`)
    REFERENCES {$this->getTableName('core/website')} (`entity_id`)
      ON DELETE RESTRICT
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('user/entity')}
  ADD CONSTRAINT `FK_USER_ENTITY_PRIMARY_GROUP_ID__USER_GROUP_GROUP_ID`
    FOREIGN KEY (`primary_group_id`)
    REFERENCES {$this->getTableName('user/group')} (`group_id`)
      ON DELETE RESTRICT
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('user/authentication')} (
   `user_entity_id`     SMALLINT UNSIGNED                   NOT NULL,
   `server_salt`        CHAR(52)            CHARSET ascii   NOT NULL,
   `server_hash`        CHAR(52)            CHARSET ascii   NOT NULL,
   PRIMARY KEY (`user_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('user/authentication', 'user_read',  array('SELECT'))
    ->grant('user/authentication', 'user_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('user/authentication', 'user_setup')
;

$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('user/authentication')}
  ADD CONSTRAINT `FK_USER_AUTHENTICATION_USER_ENTITY_ID__USER_ENTITY_ENTITY_ID`
    FOREIGN KEY (`user_entity_id`)
    REFERENCES {$this->getTableName('user/entity')} (`entity_id`)
      ON DELETE RESTRICT
      ON UPDATE CASCADE;
SQL_EOF;

