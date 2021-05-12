CREATE TABLE IF NOT EXISTS `wst_guarantee_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `saleInterval` int(11) NOT NULL DEFAULT '5' COMMENT '出售增值时间间隔',
  `backStart` int(11) NOT NULL DEFAULT '60' COMMENT '保底回购开始时间',
  `backEnd` int(11) NOT NULL DEFAULT '365' COMMENT '保底回购结束时间',
  `maxRise` decimal(11,2) NOT NULL DEFAULT '20' COMMENT '默认最高涨幅',
  `keepDays` int(11) NOT NULL DEFAULT '30' COMMENT '免费保管期',
  `chargeRate` decimal(11,2) NOT NULL DEFAULT '20' COMMENT '收费标准',
  `catSort` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `dataFlag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1-正常 0-隐藏 -1-删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
INSERT INTO `wst_guarantee_config` (id,saleInterval,backStart,backEnd,maxRise,keepDays,chargeRate) VALUES (1,5,60,365,20,30,20);
INSERT INTO `wst_navs`(navType,navTitle,navUrl,isShow,isOpen,navSort,createTime) VALUES ('0', '保底交易', 'index.php/addon/guarantee-guarantee-lists.html?isStock=1&isNew=1', '1', '0', '0', '2017-02-25 10:32:01');