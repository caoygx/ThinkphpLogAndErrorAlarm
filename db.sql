

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pm_log_request
-- ----------------------------
DROP TABLE IF EXISTS `pm_log_request`;
CREATE TABLE `pm_log_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号---sort',
  `user_id` varchar(50) DEFAULT '0' COMMENT '用户id--取cookie必须可以为null',
  `url` varchar(1024) DEFAULT '',
  `ip` char(15) DEFAULT '',
  `detail` longtext CHARACTER SET utf8mb4 COMMENT '详情|0',
  `user_agent` text COMMENT '浏览器|0',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间-datetimeRangePicker',
  `params` longtext COMMENT '参数|0',
  `method` char(6) DEFAULT '' COMMENT '请求方式',
  `cookie` varchar(1000) DEFAULT '' COMMENT 'cookie|0',
  `response` longtext CHARACTER SET utf8mb4 COMMENT '返回内容|0',
  `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间|0',
  `rinse_status` tinyint(1) DEFAULT '0' COMMENT '数据清洗状态|0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='访问日志表|lock';

-- ----------------------------
-- Table structure for pm_queue
-- ----------------------------
DROP TABLE IF EXISTS `pm_queue`;
CREATE TABLE `pm_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` text,
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='队列';
