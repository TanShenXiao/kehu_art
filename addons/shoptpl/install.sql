DROP TABLE IF EXISTS `wst_shop_templates`;
CREATE TABLE `wst_shop_templates` (
  `tplId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tplType` tinyint(4),
  `tplName` varchar(50),
  `tplPath` varchar(150),
  PRIMARY KEY (`tplId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (1,0,'默认模板', '0');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (2,0,'模板一', '1');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (3,0,'模板二', '2');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (4,0,'模板三', '3');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (5,0,'模板四', '4');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (6,0,'模板五', '5');

INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (7,1,'默认模板', '0');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (8,1,'模板一', '1');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (9,1,'模板二', '2');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (10,1,'模板三', '3');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (11,1,'模板四', '4');
INSERT INTO `wst_shop_templates` (tplId,tplType,tplName,tplPath) VALUES (12,1,'模板五', '5');

alter table `wst_shop_configs` add userTemplate tinyint(4) default 0;
alter table `wst_shop_configs` add mobileTemplate tinyint(4) default 0;