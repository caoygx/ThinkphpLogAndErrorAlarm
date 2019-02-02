# 定时脚本
*/10 * * * * /usr/bin/php /www/web/项目目录/public/index.php index/monitor/logSqlToDb
*/1  * * * * /usr/bin/php /www/web/项目目录/public/index.php index/monitor/startQueue
1. 修改application\index\behavior\Trace.php中 actionBegin()方法。里面可配置要屏蔽的url,以及定制一此要记录的信息。
