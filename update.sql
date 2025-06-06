
-- 2025-06-06
UPDATE `cmf_admin_menu` SET `parent_id`=123 WHERE  `id`=308;
UPDATE `cmf_admin_menu` SET `list_order`=7 WHERE  `id`=346;
INSERT INTO `cmf_admin_menu` (`parent_id`, `type`, `status`, `list_order`, `app`, `controller`, `action`, `param`, `name`, `icon`, `remark`) VALUES (0, 1, 1, 5, 'admin', 'report', 'default', '', '用户举报', 'user-times', '');
INSERT INTO `cmf_admin_menu` (`parent_id`, `type`, `status`, `list_order`, `app`, `controller`, `action`, `param`, `name`, `icon`, `remark`) VALUES (518, 1, 1, 5, 'admin', 'report', 'userIndex', '', '举报列表', '', '');
INSERT INTO `cmf_admin_menu` (`parent_id`, `type`, `status`, `list_order`, `app`, `controller`, `action`, `param`, `name`, `icon`, `remark`) VALUES (518, 1, 1, 4, 'admin', 'report', 'classify', '', '举报分类', '', '');
CREATE TABLE IF NOT EXISTS `cmf_report_user_classify` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `list_order` int(10) NOT NULL DEFAULT '9999' COMMENT '排序',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '举报类型名称',
    `category_title` varchar(255) NOT NULL DEFAULT '' COMMENT 'category title',
    `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
    `edittime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
INSERT INTO `cmf_report_user_classify` (`id`, `list_order`, `name`, `category_title`, `addtime`, `edittime`) VALUES (1, 0, '违法犯罪', 'Delinquency', 1544855181, 1544855181);
INSERT INTO `cmf_report_user_classify` (`id`, `list_order`, `name`, `category_title`, `addtime`, `edittime`) VALUES (2, 0, '侵犯隐私', 'Violation of Privacy', 1544855189, 1544855189);
INSERT INTO `cmf_report_user_classify` (`id`, `list_order`, `name`, `category_title`, `addtime`, `edittime`) VALUES (3, 0, '侮辱谩骂', 'Insult and abuse', 1544855198, 1544855198);
INSERT INTO `cmf_report_user_classify` (`id`, `list_order`, `name`, `category_title`, `addtime`, `edittime`) VALUES (4, 0, '其他', 'other', 1544855213, 1544855213);
INSERT INTO `cmf_report_user_classify` (`id`, `list_order`, `name`, `category_title`, `addtime`, `edittime`) VALUES (5, 0, '冒犯官员', 'Offending officials', 1544855224, 1544855224);
INSERT INTO `cmf_report_user_classify` (`id`, `list_order`, `name`, `category_title`, `addtime`, `edittime`) VALUES (6, 0, '政治敏感', 'politically sensitive', 1544855259, 1544855259);
INSERT INTO `cmf_report_user_classify` (`id`, `list_order`, `name`, `category_title`, `addtime`, `edittime`) VALUES (7, 0, '违法直播', 'Illegal live streaming', 1544855273, 1544855273);
INSERT INTO `cmf_report_user_classify` (`id`, `list_order`, `name`, `category_title`, `addtime`, `edittime`) VALUES (8, 9999, '冒充官员', 'Pretending to be an official', 1599709932, 1599709932);
INSERT INTO `cmf_report_user_classify` (`id`, `list_order`, `name`, `category_title`, `addtime`, `edittime`) VALUES (9, 9999, '造谣传谣、涉嫌欺诈', 'Spreading rumors, suspected of fraud', 1599709942, 1599709942);
CREATE TABLE IF NOT EXISTS `cmf_report_user` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
    `touid` int(11) NOT NULL DEFAULT '0' COMMENT '对方ID',
    `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
    `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '理由',
    `image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
    `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
    `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
    `uptime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `cmf_admin_menu` (`parent_id`, `type`, `status`, `list_order`, `app`, `controller`, `action`, `param`, `name`, `icon`, `remark`) VALUES (0, 1, 1, 5, 'admin', 'official', 'default', '', '官方通知', 'bell', '');
INSERT INTO `cmf_admin_menu` (`parent_id`, `type`, `status`, `list_order`, `app`, `controller`, `action`, `param`, `name`, `icon`, `remark`) VALUES (521, 1, 1, 4, 'admin', 'official', 'add', '', '通知添加', '', '');
INSERT INTO `cmf_admin_menu` (`parent_id`, `type`, `status`, `list_order`, `app`, `controller`, `action`, `param`, `name`, `icon`, `remark`) VALUES (521, 1, 1, 5, 'admin', 'official', 'log', '', '通知记录', '', '');
CREATE TABLE IF NOT EXISTS `cmf_official` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `introduction` text COMMENT '简介',
    `content` text COMMENT '内容',
    `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型 1 普通类型 2 外部链接',
    `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接',
    `push_user` varchar(255) NOT NULL DEFAULT '' COMMENT '推送者',
    `push_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '推送IP',
    `is_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推送 0 否 1 是',
    `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
    `pushtime` int(11) NOT NULL DEFAULT '0' COMMENT '推送时间',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;