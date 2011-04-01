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
    `tag`                   VARCHAR(8)          NOT NULL,
    `full_name`             VARCHAR(80)         NOT NULL,
    `short_description`     TEXT                NOT NULL,
    `description`           TEXT                NOT NULL,
    `logo`                  VARCHAR(255)        NOT NULL,
    `website_url`           VARCHAR(511)        NOT NULL,
    `updated_at`            DATETIME            NOT NULL,
    `created_at`            DATETIME            NOT NULL,
    PRIMARY KEY (`entity_id`),
    UNIQUE KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('legacies.alliance/entity', 'legacies_read',  array('SELECT'))
    ->grant('legacies.alliance/entity', 'legacies_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('legacies.alliance/entity', 'legacies_setup')
;

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies.alliance/entity.link.user')} (
    `alliance_entity_id`    INT UNSIGNED        NOT NULL,
    `user_entity_id`        INT UNSIGNED        NOT NULL,
    PRIMARY KEY (`alliance_entity_id`, `user_entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('legacies.alliance/entity.link.user', 'legacies_read',  array('SELECT'))
    ->grant('legacies.alliance/entity.link.user', 'legacies_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('legacies.alliance/entity.link.user', 'legacies_setup')
;

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies.alliance/application')} (
    `entity_id`             INT UNSIGNED        NOT NULL    AUTO_INCREMENT,
    `game_id`               INT UNSIGNED        NOT NULL,
    `tag`                   VARCHAR(8)          NOT NULL,
    `full_name`             VARCHAR(80)         NOT NULL,
    `short_description`     TEXT                NOT NULL,
    `description`           TEXT                NOT NULL,
    `logo`                  VARCHAR(255)        NOT NULL,
    `website_url`           VARCHAR(511)        NOT NULL,
    `updated_at`            DATETIME            NOT NULL,
    `created_at`            DATETIME            NOT NULL,
    PRIMARY KEY (`entity_id`),
    UNIQUE KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$this
    ->grant('legacies.alliance/application', 'legacies_read',  array('SELECT'))
    ->grant('legacies.alliance/application', 'legacies_write', array('SELECT', 'CREATE', 'UPDATE', 'DELETE'))
    ->grant('legacies.alliance/application', 'legacies_setup')
;
