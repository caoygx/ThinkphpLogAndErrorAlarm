# 安装(install)
1. 将application目录下所有内容复制到项目目录中，如果项目中已经存在文件了，千万不要覆盖，要手工合并。
2. 设置定时脚本
*/10 * * * * /usr/bin/php /www/web/项目目录/public/index.php index/monitor/logSqlToDb
*/1  * * * * /usr/bin/php /www/web/项目目录/public/index.php index/monitor/startQueue

# 相关配置修改
1. 修改application\index\behavior\Trace.php中 actionBegin()方法。里面可配置要屏蔽的url,以及定制一此要记录的信息。


# 效果预览
![报警邮件](https://github.com/caoygx/ThinkphpLogAndErrorAlarm/blob/master/alarm_email.png)