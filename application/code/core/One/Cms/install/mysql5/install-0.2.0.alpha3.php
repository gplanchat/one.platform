<?php

$this->setSetupConnection('cms_setup');

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('cms/page')} (
    `entity_id`         INT UNSIGNED        NOT NULL    AUTO_INCREMENT,
    `path`              VARCHAR(255)        NOT NULL,
    `title`             VARCHAR(255)        NOT NULL,
    `content`           TEXT                NOT NULL,
    `website_id`        TINYINT UNSIGNED    NOT NULL,
    `meta_keywords`     VARCHAR(511)        NOT NULL,
    `meta_description`  VARCHAR(511)        NOT NULL,
    `layout_updates`    TEXT                NOT NULL,
    `layout_active`     BOOL                NOT NULL,
    PRIMARY KEY (`entity_id`),
    UNIQUE KEY (`path`, `website_id`),
    KEY `IDX_CMS_PAGE__TITLE` (`title`),
    KEY `IDX_CMS_PAGE__PATH` (`path`),
    KEY `IDX_CMS_PAGE__WEBSITE_ID` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('cms/page', 'cms_read',  array('SELECT'))
    ->grant('cms/page', 'cms_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('cms/page', 'cms_setup')
;

$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('cms/page')}
  ADD CONSTRAINT `FK_CMS_PAGE_WEBSITE_ID__CORE_WEBSITE_ENTITY_ID`
    FOREIGN KEY (`website_id`)
    REFERENCES {$this->getTableName('core/website')}(`entity_id`)
      ON DELETE RESTRICT
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
INSERT INTO {$this->getTableName('cms/page')} (
    `path`,
    `title`,
    `content`,
    `website_id`,
    `meta_keywords`,
    `meta_description`,
    `layout_updates`,
    `layout_active`
    )
    VALUES
    (
        'homepage',
        'Home Page',
        '<h1>One.Platform homepage</h1>\r\n<div class=\"navigation\">\r\n<ul>\r\n<li><a href=\"account/login\">Log In</a></li>\r\n<li><a href=\"account/logout\">Log Out</a></li>\r\n<li><a href=\"admin/cms/page/\">CMS Page management</a></li>\r\n</ul>\r\n</div>',
        2,
        'cms,one,platform',
        'Tesing the CMS functionnalities of One.Platform',
        '<reference name=\"root\">\r\n</reference>',
        0
    ), (
        'no-route',
        'Page not found',
        '<h1>Page not found</h1><p>The page could not be found</p>',
        2,
        'cms,one,platform',
        'One.Platform, a PHP5 application development platform',
        '',
        0
    );
SQL_EOF;

$this->query($sql);
