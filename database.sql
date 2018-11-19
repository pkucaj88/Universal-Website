
CREATE TABLE IF NOT EXISTS `user` (
  `userid` int(8) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `name` varchar(64),
  `surname` varchar(64),
  `city` varchar(64),
  `street` varchar(64),
  `postal` varchar(32),
  `country` varchar(32),
  `phone` varchar(32),
  `ip` varchar(64),
  `createtime` datetime NOT NULL,
  `active` int(1) NOT NULL DEFAULT 1,
   PRIMARY KEY (`userid`),
   UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `validation` (
  `email` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `createtime` datetime NOT NULL,
  `code` varchar(64) NOT NULL,
  `type` int(1) NOT NULL,
  `activated` int(1) NOT NULL,
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `login` (
  `email` varchar(64) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `news` (
  `newsid` int(8) NOT NULL,
  `author` varchar(64) NOT NULL,
  `title` varchar(256) NOT NULL,
  `content` varchar(2056) NOT NULL,
  `date` datetime NOT NULL,
  `active` int(1) NOT NULL DEFAULT 1,
   PRIMARY KEY (`newsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `product` (
  `itemid` int(8) NOT NULL,
  `item_name` varchar(128) NOT NULL,
  `category` int(8) DEFAULT NULL,
  `description` varchar(2056),
  `image` varchar(64) DEFAULT NULL,
  `price` decimal(9,2) NOT NULL,
  `discount` int(2) DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT 1,
   PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `transaction` (
  `txn_id` varchar(32) NOT NULL,
  `userid` int(8) NOT NULL,
  `payer_email` varchar(64) NOT NULL,
  `item_name` varchar(128) NOT NULL,
  `payment_amount` decimal(9,2) NOT NULL,
  `payment_currency` varchar(8) NOT NULL,
  `payment_status` varchar(32) NOT NULL,
  `payment_date` datetime NOT NULL,
   PRIMARY KEY (`txn_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `gallery` (
  `imgid` int(8) NOT NULL,
  `image_name` varchar(64) DEFAULT NULL,
  `image_desc` varchar(512) DEFAULT NULL,
  `image` varchar(64) DEFAULT NULL,
   PRIMARY KEY (`imgid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

