<?php

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('core/config')} (
    `entity_id`         TINYINT UNSIGNED    NOT NULL    AUTO_INCREMENT,
    `path`              VARCHAR(512)        NOT NULL,
    `value`             TEXT                NOT NULL,
    `website_id`        TINYINT(4)          NOT NULL,

    PRIMARY KEY (`entity_id`),
    UNIQUE KEY `UNQ_CORE_CONFIG__PATH_WEBSITE_ID` (`path` (255), `website_id`),
    KEY        `IDX_CORE_CONFIG__WEBSITE_ID` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('core/config', 'core_read',  array('SELECT'))
    ->grant('core/config', 'core_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('core/config', 'core_setup')
;

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('setup/module')} (
   `entity_id`          TINYINT UNSIGNED    NOT NULL    AUTO_INCREMENT,
   `identifier`         VARCHAR(100)        NOT NULL,
   `version`            VARCHAR(20)         NOT NULL,
   `stage`              VARCHAR(10)          NOT NULL,
   PRIMARY KEY (`entity_id`),
   UNIQUE KEY `UNQ_CORE_MODULE__IDENTIFIER` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('setup/module', 'core_read',  array('SELECT'))
    ->grant('setup/module', 'core_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('setup/module', 'core_setup')
;

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('core/website')} (
   `entity_id`          TINYINT UNSIGNED    NOT NULL    AUTO_INCREMENT,
   `identity_string`    VARCHAR(100)        NOT NULL,
   `parent_website_id`  TINYINT UNSIGNED    NOT NULL,
   `label`              VARCHAR(100)        NOT NULL,
   PRIMARY KEY (`entity_id`),
   KEY `IDX_CORE_WEBSITE__PARENT_WEBSITE_ID` (`parent_website_id`),
   KEY `IDX_CORE_WEBSITE__LABEL` (`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('core/website', 'core_read',  array('SELECT'))
    ->grant('core/website', 'core_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('core/website', 'core_setup')
;

$sql = <<<SQL_EOF
INSERT INTO {$this->getTableName('core/website')} (`entity_id`, `identity_string`, `parent_website_id`, `label`) VALUES
    ('1', 'backoffice', '1', 'Back Office'),
    ('2', 'frontoffice', '1', 'Front Office');
SQL_EOF;

$this->query($sql);
