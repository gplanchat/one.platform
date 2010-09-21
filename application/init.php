<?php

$sql = array();
$sql[] = <<<SQL_EOF
CREATE TABLE cms_route (
  entity_id         INTEGER PRIMARY KEY,
  locale_id         CHAR(2) PRIMARY KEY,
  path              VARCHAR,
  parent_entity_id  INTEGER,
  page_entity_id    INTEGER
  );
SQL_EOF;

$sql[] = <<<SQL_EOF
CREATE TABLE cms_page (
  entity_id         INTEGER PRIMARY KEY,
  locale_id         CHAR(2) PRIMARY KEY
  title             VARCHAR,
  content           TEXT
  );
SQL_EOF;

