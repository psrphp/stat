<?php

declare(strict_types=1);

namespace App\Psrphp\Stat\Psrphp;

use PsrPHP\Framework\Script as FrameworkScript;

class Script
{
    public static function onInstall()
    {
        $sql = <<<'str'
DROP TABLE IF EXISTS `prefix_psrphp_stat_log`;
CREATE TABLE `prefix_psrphp_stat_log` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `guid` char(36) NOT NULL COMMENT '访客id',
    `url` varchar(255) COMMENT '访问页面',
    `referer` varchar(255) COMMENT 'http referer',
    `from` varchar(255) COMMENT '',
    `first` tinyint(3) unsigned NOT NULL COMMENT '是否是新访客',
    `ip` varchar(255) COMMENT 'IP',
    `user_agent` varchar(255) COMMENT 'user agent',
    `type` varchar(255) COMMENT '客户端类型 pc ios android other',
    `date` date NOT NULL COMMENT '访问日期',
    `datetime` datetime NOT NULL COMMENT '访问时间',
    `country` varchar(255) COMMENT '国家',
    `province` varchar(255) COMMENT '省份',
    `city` varchar(255) COMMENT '城市',
    `ISP` varchar(255) COMMENT '电信服务提供商',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='访问记录';
str;
        FrameworkScript::execSql($sql);
    }

    public static function onUnInstall()
    {
        $sql = <<<'str'
DROP TABLE IF EXISTS `prefix_psrphp_stat_log`;
str;
        FrameworkScript::execSql($sql);
    }
}
