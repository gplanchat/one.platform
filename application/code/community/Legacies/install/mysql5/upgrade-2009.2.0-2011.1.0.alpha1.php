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
try {

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

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies.alliance/application')} (
    `application_id`        BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `alliance_entity_id`    INT UNSIGNED        NOT NULL,
    `user_entity_id`        SMALLINT UNSIGNED   NOT NULL,
    `text`                  TEXT                NOT NULL,
    `created_at`            DATETIME            NOT NULL,
    `updated_at`            DATETIME            NOT NULL,
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
ALTER TABLE {$this->getTableName('legacies.alliance/application')}
  ADD CONSTRAINT `FK_LEGACIES_ALLIANCE_APPLICATION__USER_ENTITY`
    FOREIGN KEY (`user_entity_id`)
    REFERENCES {$this->getTableName('user/entity')} (`entity_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

/*
 * Alliance application <-> Alliance constraint
 */
$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('legacies.alliance/application')}
  ADD CONSTRAINT `FK_LEGACIES_ALLIANCE_APPLICATION__LEGACIES_ALLIANCE_ENTITY`
    FOREIGN KEY (`alliance_entity_id`)
    REFERENCES {$this->getTableName('legacies.alliance/entity')} (`entity_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

$this
    ->grant('legacies.alliance/application', 'legacies_read',  array('SELECT'))
    ->grant('legacies.alliance/application', 'legacies_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('legacies.alliance/application', 'legacies_setup')
;

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies.alliance/rank')} (
    `rank_id`                   INT UNSIGNED        NOT NULL    AUTO_INCREMENT,
    `alliance_entity_id`        INT UNSIGNED        NOT NULL,
    `name`                      VARCHAR(50)         NOT NULL,
    `description`               VARCHAR(255)        NOT NULL,
    `acl_delete`                BOOL                NOT NULL,
    `acl_kick`                  BOOL                NOT NULL,
    `acl_show_applications`     BOOL                NOT NULL,
    `acl_memberlist`            BOOL                NOT NULL,
    `acl_manage_applications`   BOOL                NOT NULL,
    `acl_edit`                  BOOL                NOT NULL,
    `acl_show_online`           BOOL                NOT NULL,
    `acl_messages`              BOOL                NOT NULL,
    `acl_right_hand`            BOOL                NOT NULL,
    `created_at`                DATETIME            NOT NULL,
    `updated_at`                DATETIME            NOT NULL,
    PRIMARY KEY (`rank_id`),
    INDEX `IDX_ALLIANCE_ENTITY_ID` (`alliance_entity_id`),
    UNIQUE `UNQ_NAME` (`alliance_entity_id`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

/*
 * Alliance rank <-> Alliance constraint
 */
$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('legacies.alliance/rank')}
  ADD CONSTRAINT `FK_LEGACIES_ALLIANCE_RANK__LEGACIES_ALLIANCE`
    FOREIGN KEY (`alliance_entity_id`)
    REFERENCES {$this->getTableName('legacies.alliance/entity')} (`entity_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

$this
    ->grant('legacies.alliance/rank', 'legacies_read',  array('SELECT'))
    ->grant('legacies.alliance/rank', 'legacies_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('legacies.alliance/rank', 'legacies_setup')
;

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies.alliance/entity.link.user')} (
    `alliance_entity_id`    INT UNSIGNED        NOT NULL,
    `user_entity_id`        SMALLINT UNSIGNED   NOT NULL,
    `rank_id`               INT UNSIGNED        NOT NULL,
    `created_at`            DATETIME            NOT NULL,
    `updated_at`            DATETIME            NOT NULL,
    PRIMARY KEY (`alliance_entity_id`, `user_entity_id`),
    INDEX `IDX_ALLIANCE_ENTITY_ID` (`alliance_entity_id`),
    INDEX `IDX_USER_ENTITY_ID` (`user_entity_id`),
    INDEX `IDX_RANK_ID` (`rank_id`)
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

/*
 * Alliance link <-> Alliance rank constraint
 */
$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('legacies.alliance/entity.link.user')}
  ADD CONSTRAINT `FK_LEGACIES_ALLIANCE_ENTITY_LINK_USER__ALLIANCE_RANK`
    FOREIGN KEY (`rank_id`)
    REFERENCES {$this->getTableName('legacies.alliance/rank')} (`rank_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE;
SQL_EOF;

$this->query($sql);

$this
    ->grant('legacies.alliance/entity.link.user', 'legacies_read',  array('SELECT'))
    ->grant('legacies.alliance/entity.link.user', 'legacies_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('legacies.alliance/entity.link.user', 'legacies_setup')
;

/*
 * Migration requests starts here
 */
$sql = <<<SQL_EOF
INSERT INTO {$this->getTableName('legacies.alliance/entity')} (
    `entity_id`, `game_id`, `manager_entity_id`, `tag`, `full_name`,
    `short_description`, `description`, `private_notes`, `logo`, `website_url`,
    `updated_at`, `created_at`
    )
  SELECT alliance.`id`, 1, alliance.`ally_owner`, alliance.`ally_tag`,
      alliance.`ally_name`, alliance.`ally_description`, alliance.`ally_description`,
      alliance.`ally_description`, alliance.`ally_image`, alliance.`ally_web`,
      NOW(), NOW()
    FROM {$this->getTableName('legacies/alliance')} AS alliance;
SQL_EOF;

$this->query($sql);

$userSelect = $this->select()
    ->from(array(
        'user' => $this->getTableName('legacies/users', false)
        ), array(
            'user_entity_id'     => 'id',
            'rank_id'            => 'ally_rank_id',
            'created_at'         => 'ally_register_time'
            ))
    ->where('ally_id=?')
    ->where('ally_request=0');

$allianceSelect = $this->select()
    ->from(array(
        'alliance' => $this->getTableName('legacies/alliance', false),
        ), array(
            'entity_id' => 'id',
            'ranks'     => 'ally_ranks'
        ));
$allianceStatement = $allianceSelect->query();

foreach ($allianceStatement->fetchAll() as $alliance) {
    $ranks = unserialize($alliance['ranks']);

    if (!is_array($ranks) || empty($ranks)) {
        $ranks[] = array('name' => 'Member');
    }

    $index = array();
    foreach ($ranks as $rankId => $rank) {
        $this->insert($this->getTableName('legacies.alliance/rank', false), array(
            'alliance_entity_id'      => $alliance['entity_id'],
            'name'                    => $rank['name'],
            'description'             => '',
            'acl_delete'              => isset($rank['delete'])                ? $rank['delete']                : false,
            'acl_kick'                => isset($rank['kick'])                  ? $rank['kick']                  : false,
            'acl_show_applications'   => isset($rank['bewerbungen'])           ? $rank['bewerbungen']           : false,
            'acl_memberlist'          => isset($rank['memberlist'])            ? $rank['memberlist']            : false,
            'acl_manage_applications' => isset($rank['bewerbungenbearbeiten']) ? $rank['bewerbungenbearbeiten'] : false,
            'acl_edit'                => isset($rank['administrieren'])        ? $rank['administrieren']        : false,
            'acl_show_online'         => isset($rank['onlinestatus'])          ? $rank['onlinestatus']          : false,
            'acl_messages'            => isset($rank['mails'])                 ? $rank['mails']                 : false,
            'acl_right_hand'          => isset($rank['rechtehand'])            ? $rank['rechtehand']            : false,
            'created_at'              => new Zend_Db_Expr('NOW()'),
            'updated_at'              => new Zend_Db_Expr('NOW()')
            ));
        $index[$rankId] = $this->lastInsertId($this->getTableName('legacies.alliance/rank', false));
    }

    $select = clone $userSelect;
    $userStatement = $select->query(Zend_Db::FETCH_ASSOC, array($alliance['entity_id']));

    foreach ($userStatement->fetchAll() as $user) {
        $this->insert($this->getTableName('legacies.alliance/entity.link.user', false), array(
            'user_entity_id'     => $user['user_entity_id'],
            'alliance_entity_id' => $alliance['entity_id'],
            'rank_id'            => $index[$user['rank_id']],
            'updated_at'         => new Zend_Db_Expr('NOW()'),
            'created_at'         => $user['created_at']
            ));
    }
    unset($index);
}

// Migration user applications
$sql = <<<SQL_EOF
INSERT INTO {$this->getTableName('legacies.alliance/application')} (
    `user_entity_id`, `alliance_entity_id`, `text`, `updated_at`, `created_at`
  )
  SELECT
      user.`id`,
      user.`ally_id`,
      user.`ally_request_text`,
      NOW(),
      FROM_UNIXTIME(user.`ally_register_time`)
    FROM {$this->getTableName('legacies/users')} AS user
      WHERE user.`ally_request`=1
SQL_EOF;

$this->query($sql);

// Cleaning up legacy user table
$sql = <<<SQL_EOF
ALTER TABLE {$this->getTableName('legacies/users')}
  DROP COLUMN `ally_id`,
  DROP COLUMN `ally_name`,
  DROP COLUMN `ally_request`,
  DROP COLUMN `ally_request_text`,
  DROP COLUMN `ally_register_time`,
  DROP COLUMN `ally_rank_id`
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('legacies/alliance')};
SQL_EOF;

$this->query($sql);
} catch (Exception $e) {
    echo "<pre>$e</pre";
    die();
}
