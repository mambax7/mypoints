#
# Table structure for table `mypoints_userpoints`
#

CREATE TABLE mypoints_user (
  useruid    INT(10)          NOT NULL DEFAULT '0',
  useruname  VARCHAR(50)      NOT NULL DEFAULT '',
  userpoints INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (useruid),
  KEY uuname (useruname)
)
  ENGINE = MyISAM;

#
# Table structure for table `mypoints_plugins`
#

CREATE TABLE mypoints_plugin (
  pluginid       INT(10) UNSIGNED        NOT NULL AUTO_INCREMENT,
  pluginmid      SMALLINT(4) UNSIGNED    NOT NULL DEFAULT '0',
  pluginname     VARCHAR(50)             NOT NULL DEFAULT '',
  plugintype     ENUM ('items', 'votes') NOT NULL DEFAULT 'items',
  pluginmulti    INT(10) UNSIGNED        NOT NULL DEFAULT '1',
  pluginisactive INT(10) UNSIGNED        NOT NULL DEFAULT '1',
  PRIMARY KEY (pluginid),
  KEY pmid (pluginmid)
)
  ENGINE = MyISAM;

#
# Table structure for table `mypoints_plugins`
#

CREATE TABLE mypoints_relation (
  relationid     INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  relationuid    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  relationpid    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  relationpoints INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (relationid),
  KEY ruid (relationuid)
)
  ENGINE = MyISAM;
