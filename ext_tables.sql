#
# Table structure for table 'tx_tgmreveal_domain_model_reveal'
#

CREATE TABLE tx_tgmreveal_domain_model_reveal (

  uid              INT(11)                         NOT NULL auto_increment,
  pid              INT(11) DEFAULT '0'             NOT NULL,

  tstamp           INT(11) unsigned DEFAULT '0'    NOT NULL,
  crdate           INT(11) unsigned DEFAULT '0'    NOT NULL,
  cruser_id        INT(11) unsigned DEFAULT '0'    NOT NULL,
  deleted          TINYINT(4) unsigned DEFAULT '0' NOT NULL,
  hidden           TINYINT(4) unsigned DEFAULT '0' NOT NULL,
  starttime        INT(11) unsigned DEFAULT '0'    NOT NULL,
  endtime          INT(11) DEFAULT '0'             NOT NULL,

  t3ver_oid        INT(11) DEFAULT '0'             NOT NULL,
  t3ver_id         INT(11) DEFAULT '0'             NOT NULL,
  t3ver_wsid       INT(11) DEFAULT '0'             NOT NULL,
  t3ver_label      VARCHAR(255) DEFAULT ''         NOT NULL,
  t3ver_state      TINYINT(4) DEFAULT '0'          NOT NULL,
  t3ver_stage      INT(11) DEFAULT '0'             NOT NULL,
  t3ver_count      INT(11) DEFAULT '0'             NOT NULL,
  t3ver_tstamp     INT(11) DEFAULT '0'             NOT NULL,
  t3ver_move_id    INT(11) DEFAULT '0'             NOT NULL,

  sys_language_uid INT(11) DEFAULT '0'             NOT NULL,
  l10n_parent      INT(11) DEFAULT '0'             NOT NULL,
  l10n_diffsource  mediumblob,

  PRIMARY KEY (uid),
  KEY parent (pid)

);

CREATE TABLE pages (

  tx_tgm_reveal_bg_type                 VARCHAR(55) DEFAULT ''        NOT NULL,
  tx_tgm_reveal_bg_none                 VARCHAR(5) DEFAULT 'true'     NOT NULL,
  tx_tgm_reveal_bg_color                VARCHAR(50) DEFAULT ''        NOT NULL,
  tx_tgm_reveal_bg_image_selectBy       VARCHAR(5) DEFAULT ''         NOT NULL,
  tx_tgm_reveal_bg_image_selectBy_fal   VARCHAR(255) DEFAULT ''       NOT NULL,
  tx_tgm_reveal_bg_image_selectBy_link  VARCHAR(255) DEFAULT ''       NOT NULL,
  tx_tgm_reveal_bg_image_size           VARCHAR(80) DEFAULT ''        NOT NULL,
  tx_tgm_reveal_bg_image_position       VARCHAR(80) DEFAULT ''        NOT NULL,
  tx_tgm_reveal_bg_image_repeat         VARCHAR(80) DEFAULT ''        NOT NULL,
  tx_tgm_reveal_bg_video                VARCHAR(255) DEFAULT ''       NOT NULL,
  tx_tgm_reveal_bg_video_selectBy       VARCHAR(5) DEFAULT ''         NOT NULL,
  tx_tgm_reveal_bg_video_selectBy_fal   VARCHAR(255) DEFAULT ''       NOT NULL,
  tx_tgm_reveal_bg_video_selectBy_link  VARCHAR(255) DEFAULT ''       NOT NULL,
  tx_tgm_reveal_bg_video_loop           VARCHAR(5) DEFAULT 'false'    NOT NULL,
  tx_tgm_reveal_bg_video_muted          VARCHAR(5) DEFAULT 'false'    NOT NULL,
  tx_tgm_reveal_bg_iframe               VARCHAR(255) DEFAULT ''       NOT NULL,
  tx_tgm_reveal_notes                   VARCHAR(255) DEFAULT ''       NOT NULL,
  tx_tgm_reveal_transition              VARCHAR(55) DEFAULT ''        NOT NULL,
  tx_tgm_reveal_state                   VARCHAR(55) DEFAULT ''        NOT NULL,
  tx_tgm_reveal_markdown                VARCHAR(5) DEFAULT 'false'    NOT NULL,
  tx_tgm_reveal_trim                    VARCHAR(5) DEFAULT 'false'    NOT NULL,
  tx_tgm_reveal_additional_attributes   VARCHAR(255) DEFAULT ''       NOT NULL

);