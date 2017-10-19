CREATE TABLE tt_content (
  sudhaus7_flexform text
);
#
# TABLE STRUCTURE FOR TABLE 'cf_sudhaus7fetchcontent_cache'
#
CREATE TABLE cf_sudhaus7fetchcontent_cache (
    id INT(11) unsigned NOT NULL auto_increment,
    identifier VARCHAR(250)  NOT NULL DEFAULT '',
    crdate INT(11) unsigned NOT NULL DEFAULT '0' ,
    content mediumblob,
    lifetime INT(11) unsigned NOT NULL DEFAULT '0' ,
    PRIMARY KEY (id),
    KEY cache_id (identifier)
) ENGINE=InnoDB;

#
# TABLE STRUCTURE FOR TABLE 'cf_sudhaus7fetchcontent_cache_tags'
#
CREATE TABLE cf_sudhaus7fetchcontent_cache_tags (
    id INT(11) unsigned NOT NULL auto_increment,
    identifier VARCHAR(250) DEFAULT '' NOT NULL,
    tag VARCHAR(250) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY cache_id (identifier),
    KEY cache_tag (tag)
) ENGINE=InnoDB;
