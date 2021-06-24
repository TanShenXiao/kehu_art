CREATE TABLE IF NOT EXISTS `wst_vote_cats` (
  `catId` int(11) NOT NULL AUTO_INCREMENT,
  `catName` varchar(100) NOT NULL DEFAULT '',
  `catDesc` text,
  `catImage` varchar(255),
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `totalVotes` int(11) NOT NULL DEFAULT '1' COMMENT '每人每日最大投票数',
  `itemVotes` int(11) NOT NULL DEFAULT '1' COMMENT '每人每项目每日最大投票数',
  `catSort` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1-正常 0-隐藏 -1-删除',
  PRIMARY KEY (`catId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `wst_navs`(navType,navTitle,navUrl,isShow,isOpen,navSort,createTime) VALUES ('0', '在线投票', 'index.php/addon/vote-vote-lists.html', '1', '0', '0', '2018-09-26 10:32:01');
CREATE TABLE IF NOT EXISTS `wst_vote_lists` (
  `listId` int(11) NOT NULL AUTO_INCREMENT,
  `catId` int(11) NOT NULL DEFAULT '0',
  `catName` varchar(100) NOT NULL DEFAULT '',
  `itemId` int(11) NOT NULL DEFAULT '0' COMMENT '投票项id',
  `itemName` varchar(100) NOT NULL DEFAULT '' COMMENT '投票项名字',
  `userId` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `userName` varchar(100) COMMENT '用户名称',
  `createTime` datetime,
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1-正常 0-隐藏 -1-删除',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '投票状态 0：禁用 1：启用',
  PRIMARY KEY (`listId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `wst_vote_items` (
  `itemId` int(11) NOT NULL AUTO_INCREMENT,
  `catId` int(11) NOT NULL DEFAULT '0',
  `catName` varchar(100) NOT NULL DEFAULT '',
  `itemName` varchar(100) NOT NULL DEFAULT '' COMMENT '扩展名字',
  `itemUrl` varchar(255) NOT NULL DEFAULT '' COMMENT '投票项链接',
  `isShow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `itemDesc` text,
  `itemImage` varchar(255),
  `itemSort` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '删除标志',
  PRIMARY KEY (`itemId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `wst_vote_config` (
  `totalVotes` int(11) NOT NULL DEFAULT '1' COMMENT '每人每日最大投票数'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `wst_vote_config`(totalVotes) VALUES ('3');