CREATE TABLE IF NOT EXISTS `wst_signup_cats` (
  `catId` int(11) NOT NULL AUTO_INCREMENT,
  `catName` varchar(100) NOT NULL DEFAULT '',
  `catDesc` text,
  `afterSignup` text,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `needPay` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否需要付费',
  `signupFee` decimal(11,2) COMMENT '付费金额',
  `catSort` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1-正常 0-隐藏 -1-删除',
  `signupLimit` int(11) NOT NULL DEFAULT '0' COMMENT '报名人数限制，0为不限制',
  PRIMARY KEY (`catId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `wst_navs`(navType,navTitle,navUrl,isShow,isOpen,navSort,createTime) VALUES ('0', '线上报名', 'index.php/addon/signup-signup-lists.html', '1', '0', '0', '2018-09-26 10:32:01');
CREATE TABLE IF NOT EXISTS `wst_signup_lists` (
  `listId` int(11) NOT NULL AUTO_INCREMENT,
  `catId` int(11) NOT NULL DEFAULT '0',
  `catName` varchar(100) NOT NULL DEFAULT '',
  `userId` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '报名者姓名',
  `instructor` varchar(100) NOT NULL DEFAULT '' COMMENT '指导老师',
  `institute` varchar(255) NOT NULL DEFAULT '' COMMENT '推荐机构',
  `telephone` varchar(20) COMMENT '推荐机构联系电话',
  `address` text COMMENT '推荐机构地址',
  `createTime` datetime,
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1-正常 0-隐藏 -1-删除',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '线上报名状态 0：禁用 1：启用',
  `isPaid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已支付 0：未支付 1：已支付',
  `payCode` varchar(20) COMMENT '支付方式',
  `tradeNo` varchar(100) COMMENT '支付流水号',
  `signupFee`  decimal(11,2) DEFAULT '0' COMMENT '报名费',
  `signupSn`  varchar(100) DEFAULT '' COMMENT '报名流水号',
  `payTime` datetime DEFAULT NULL,
  PRIMARY KEY (`listId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `wst_signup_extras` (
  `extraId` int(11) NOT NULL AUTO_INCREMENT,
  `catId` int(11) NOT NULL DEFAULT '0',
  `catName` varchar(100) NOT NULL DEFAULT '',
  `extraName` varchar(100) NOT NULL DEFAULT '' COMMENT '扩展名字',
  `isShow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `isRequired` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否必填',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '删除标志',
  `extraSort` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`extraId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `wst_signup_lists_extras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catId` int(11) NOT NULL DEFAULT '0',
  `catName` varchar(100) NOT NULL DEFAULT '',
  `listId` int(11) NOT NULL DEFAULT '0',
  `extraId` int(11) NOT NULL DEFAULT '0',
  `extraName` varchar(100) NOT NULL DEFAULT '' COMMENT '扩展名字',
  `extraVal` varchar(100) NOT NULL DEFAULT '' COMMENT '扩展内容',
  `isShow` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '删除标志',
  `telephone` varchar(20) COMMENT '推荐机构联系电话',
  `address` text COMMENT '推荐机构地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;