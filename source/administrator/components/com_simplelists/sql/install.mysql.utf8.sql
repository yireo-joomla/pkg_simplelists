CREATE TABLE IF NOT EXISTS `#__simplelists_items` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `alias` VARCHAR(255) NOT NULL DEFAULT '',
    `link_type` VARCHAR(255) NOT NULL DEFAULT 0,
    `link` VARCHAR(255) NOT NULL DEFAULT '',
    `text` TEXT NOT NULL DEFAULT '',
    `picture` TEXT NOT NULL DEFAULT '',
    `published` TINYINT(1) NOT NULL DEFAULT 0,
    `access` INT(11) NOT NULL DEFAULT 0,
    `checked_out` INT(11) NOT NULL DEFAULT 0,
    `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` INT(11) NOT NULL DEFAULT 0,
    `created_by_alias` TEXT NOT NULL DEFAULT '',
    `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` INT(11) NOT NULL DEFAULT 0,
    `modified_by_alias` TEXT NOT NULL DEFAULT '',
    `ordering` INT(11) NOT NULL DEFAULT 0,
    `hits` INT(11) NOT NULL DEFAULT 0,
    `params` TEXT NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__simplelists_categories` (
    `id` INT(11) NOT NULL DEFAULT 0,
    `category_id` INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY  (`id`,`category_id`)
) DEFAULT CHARSET=utf8;

