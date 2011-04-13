<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license Modified BSD
 * @see https://github.com/gplanchat/one.platform
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     - Neither the name of Grégory PLANCHAT nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing One.Platform.
 *
 */

$this->setSetupConnection('legacies_setup');

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies.alliance/entity')} (
    `entity_id`             INT UNSIGNED        NOT NULL    AUTO_INCREMENT,
    `game_id`               INT UNSIGNED        NOT NULL,
    `manager_entity_id`     SMALLINT UNSIGNED   NOT NULL,
    `tag`                   VARCHAR(8)          NOT NULL,
    `full_name`             VARCHAR(80)         NOT NULL,
    `short_description`     TEXT                NOT NULL,
    `description`           TEXT                NOT NULL,
    `private_notes`         TEXT                NOT NULL,
    `logo`                  VARCHAR(255)        NOT NULL,
    `website_url`           VARCHAR(511)        NOT NULL,
    `updated_at`            DATETIME            NOT NULL,
    `created_at`            DATETIME            NOT NULL,
    PRIMARY KEY (`entity_id`),
    UNIQUE KEY `UNQ_TAG` (`tag`),
    INDEX `IDX_MANAGER_ENTITY_ID` (`manager_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

/*
 * Alliance <-> User constraint
 */
$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('legacies.alliance/entity')}
  ADD CONSTRAINT `FK_LEGACIES_ALLIANCE_ENTITY__USER_ENTITY`
    FOREIGN KEY (`manager_entity_id`)
    REFERENCES {$this->getTableName('user/entity')} (`entity_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

$this
    ->grant('legacies.alliance/entity', 'legacies_read',  array('SELECT'))
    ->grant('legacies.alliance/entity', 'legacies_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('legacies.alliance/entity', 'legacies_setup')
;

//$sql = <<<SQL_EOF
//INSERT INTO {$this->getTableName('legacies.alliance/entity')} (
//  `entity_id`, `game_id`, `manager_entity_id`, `tag`, `full_name`,
//  `short_description`, `description`, `private_notes`, `logo`, `website_url`,
//  `updated_at`, `created_at`
//  )
//  SELECT alliance.`id`, 1, alliance.`ally_owner`, alliance.`ally_tag`,
//      alliance.`ally_name`, alliance.`ally_description`, alliance.`ally_description`,
//      alliance.`ally_description`, alliance.`ally_image`, alliance.`ally_web`,
//      NOW(), NOW()
//  FROM {$this->getTableName('legacies/alliance')} AS alliance;
//SQL_EOF;
//
//$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies.alliance/entity.link.user')} (
    `alliance_entity_id`    INT UNSIGNED        NOT NULL,
    `user_entity_id`        SMALLINT UNSIGNED   NOT NULL,
    PRIMARY KEY (`alliance_entity_id`, `user_entity_id`),
    INDEX `IDX_ALLIANCE_ENTITY_ID` (`alliance_entity_id`),
    INDEX `IDX_USER_ENTITY_ID` (`user_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

/*
 * Alliance link <-> Alliance constraint
 */
$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('legacies.alliance/entity.link.user')}
  ADD CONSTRAINT `FK_LEGACIES_ALLIANCE_ENTITY_LINK_USER__LEGACIES_ALLIANCE`
    FOREIGN KEY (`alliance_entity_id`)
    REFERENCES {$this->getTableName('legacies.alliance/entity')} (`entity_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

/*
 * Alliance link <-> User constraint
 */
$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('legacies.alliance/entity.link.user')}
  ADD CONSTRAINT `FK_LEGACIES_ALLIANCE_ENTITY_LINK_USER__USER_ENTITY`
    FOREIGN KEY (`user_entity_id`)
    REFERENCES {$this->getTableName('user/entity')} (`entity_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

$this
    ->grant('legacies.alliance/entity.link.user', 'legacies_read',  array('SELECT'))
    ->grant('legacies.alliance/entity.link.user', 'legacies_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('legacies.alliance/entity.link.user', 'legacies_setup')
;

//$sql = <<<SQL_EOF
//SELECT alliance.`id`, alliance.`ally_members` FROM {$this->getTableName('legacies/alliance')} AS alliance;
//SQL_EOF;
//
//$statement = $this->query($sql);
//foreach ($statement as $row) {
//  TODO: Migration routines
//}

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies.alliance/application')} (
    `application_id`        BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `alliance_entity_id`    INT UNSIGNED        NOT NULL,
    `user_entity_id`        SMALLINT UNSIGNED   NOT NULL,
    `text`                  TEXT                NOT NULL,
    PRIMARY KEY (`application_id`),
    INDEX (`alliance_entity_id`),
    INDEX (`user_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

/*
 * Alliance application <-> User constraint
 */
$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('legacies.alliance/entity.link.user')}
  ADD CONSTRAINT `FK_LEGACIES_ALLIANCE_APPLICATION__USER_ENTITY`
    FOREIGN KEY (`user_entity_id`)
    REFERENCES {$this->getTableName('user/entity')} (`entity_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE;
SQL_EOF;

/*
 * Alliance application <-> Alliance constraint
 */
$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('legacies.alliance/entity.link.user')}
  ADD CONSTRAINT `FK_LEGACIES_ALLIANCE_APPLICATION__LEGACIES_ALLIANCE_ENTITY`
    FOREIGN KEY (`alliance_entity_id`)
    REFERENCES {$this->getTableName('legacies.alliance/entity')} (`entity_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE;
SQL_EOF;

$this
    ->grant('legacies.alliance/application', 'legacies_read',  array('SELECT'))
    ->grant('legacies.alliance/application', 'legacies_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('legacies.alliance/application', 'legacies_setup')
;