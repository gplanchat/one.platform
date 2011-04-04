<?php

$this->setSetupConnection('cms_setup');

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('cms/gadget')} (
    `entity_id`         INT UNSIGNED        NOT NULL    AUTO_INCREMENT,
    `identifier`        VARCHAR(40)         NOT NULL,
    `title`             VARCHAR(255)        NOT NULL,
    `content`           TEXT                NOT NULL,
    `website_id`        TINYINT UNSIGNED    NOT NULL,
    `layout_updates`    TEXT                NOT NULL,
    `layout_active`     BOOL                NOT NULL,
    PRIMARY KEY (`entity_id`),
    UNIQUE KEY (`identifier`, `website_id`),
    KEY `IDX_CMS_GAGET__TITLE` (`title`),
    KEY `IDX_CMS_GAGET__IDENTIFIER` (`identifier`),
    KEY `IDX_CMS_GAGET__WEBSITE_ID` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('cms/gadget', 'cms_read',  array('SELECT'))
    ->grant('cms/gadget', 'cms_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('cms/gadget', 'cms_setup')
;

$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('cms/gadget')}
  ADD CONSTRAINT `FK_CMS_GAGET_WEBSITE_ID__CORE_WEBSITE_ENTITY_ID`
    FOREIGN KEY (`website_id`)
    REFERENCES {$this->getTableName('core/website')}(`entity_id`)
      ON DELETE RESTRICT
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);
