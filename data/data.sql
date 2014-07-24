DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `wechat_id` varchar(128) NOT NULL DEFAULT '' COMMENT '微信openid',
  `subscribe` int unsigned NOT NULL DEFAULT '0' COMMENT '该用户关注帐号的时间',
  `last_update` int unsigned NOT NULL DEFAULT '0' COMMENT '该用户最近访问的时间',
  `status` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '用户的状态：1.关注 2.取消关注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wechat_id` (`wechat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `t_basictips`
--
DROP TABLE IF EXISTS `t_basictips`;
CREATE TABLE `t_basictips` (
  `id` bigint unsigned NOT NULL auto_increment COMMENT '主键',
  `tips_type` int unsigned NOT NULL default '0' COMMENT 'Tips ID',
  `tips_info` varchar(4096) NOT NULL default '' COMMENT '给用户的提示信息',
  `status` smallint unsigned NOT NULL default '1' COMMENT '对应问题当前的状态',
  `last_modify` varchar(40) NOT NULL default ''  COMMENT '最后修改问题的负责人',
  `update_time` int unsigned NOT NULL default '0'  COMMENT '最后修改问题的时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Table structure for table `t_authorize`
--
DROP TABLE IF EXISTS `t_authorize`;
CREATE TABLE `t_authorize` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `wechat_id` varchar(128) NOT NULL DEFAULT '' COMMENT '微信opendid',
  `access_token` varchar(256) NOT NULL DEFAULT '' COMMENT 'access token',
  `refresh_token` varchar(256) NOT NULL DEFAULT '' COMMENT '刷新用token',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wechat_id` (`wechat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
