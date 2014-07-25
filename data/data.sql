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



INSERT INTO `t_basictips` (`id`, `tips_type`, `tips_info`, `status`, `last_modify`, `update_time`) VALUES
(17, 1, '难住您了？看来我要好好加油做好体验拉! ', 1, 'bihe0832', 1369982990),
(19, 1, '如果目前的帮助都没能帮到您或者如果您有任何建议，可以回复“反馈 反馈的建议内容”（空格隔开哟）给我们。', 1, 'bihe0832', 1),
(20, 2, '感谢您的支持，您的建议是我们优化的方向。', 1, 'bihe0832', 1),
(21, 2, '哎哟，太感谢了噶！您的建议，小哈都已经记在心里了，有大家的支持，小哈相信会做的更好。', 1, 'bihe0832', 1369983001),
(22, 2, '感谢您的协助，一路走来，因为有您。', 1, 'bihe0832', 1),
(23, 3, '唉，您难住小哈了呢，小哈目前不会说话，也听不懂别人说什么呢，主要您能把写下来，小哈一定不会让你失望滴！', 1, 'bihe0832', 1370588150),
(24, 3, '啊啊啊……小哈不会说话，也听不懂别人说什么，您只能一个字一个字的告诉小哈咯……小哈也会努力学习说话的', 1, 'bihe0832', 1370588349),
(25, 3, '你还不知道吧，小哈还小，不会说话，也听不懂别人说什么，不过认识不少字呢……你可以打几个字试试他', 1, 'bihe0832', 1370588408),
(26, 4, '哎哟，上图了呀……可是小哈目前还木有学会看图的本领呢，所以您还是要一个字一个字的说给小哈滴……', 1, 'bihe0832', 1370588988),
(27, 4, '图来了呀……小哈目前还在学习中，看不懂图的，所以您只能给小哈发文字滴……', 1, 'bihe0832', 1370589818),
(28, 4, '如果您在游戏中遇到了问题，可以直接用文字描述相关信息，如果小哈懂，就一定会给您解答的……', 1, 'bihe0832', 1370589872),
(29, 5, '哎哟，这是哪里？小哈都不知道呀？还有，你要做什么呀？', 1, 'bihe0832', 1370589924),
(30, 5, '警察叔叔曾经告诉小哈，不要和陌生人约会，所以你给的地点我是不会去滴', 1, 'bihe0832', 1370589974),
(31, 5, '小哈目前还没那么强大呢，只能解决您在游戏中遇到的问题，您可以回复“？”了解一下小哈', 1, 'bihe0832', 1370590015),
(33, 10, '谢天谢地，您来了！等死小哈了呢，以后有什么问题你都尽管抛过来吧，没有小哈解决不了的呢。', 1, 'bihe0832', 1369982981),
(34, 10, '感谢您关注小哈，小哈是你最忠实的小助手，一路走来，因为有您。', 1, 'bihe0832', 1),
(35, 11, '小哈感谢您的支持，如果您有任何建议，可以回复“反馈 反馈的建议内容”（空格隔开哟）给我们。小哈期待您的回归！', 1, 'bihe0832', 1),
(36, 11, '山无棱，天地合，才敢与君绝！小哈会一直等你回来的，也相信您还会关注我的。', 1, 'bihe0832', 1369982944),
(38, 12, '看吧，还是小哈好，感谢您再次关注微助手，小哈一定给您最给力的帮助。', 1, 'bihe0832', 1);