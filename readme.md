# 安装(install)
1. 将根目录下db.sql中的表导入到数据库中
2. 将application目录下所有内容复制到项目目录中，如果项目中已经存在文件了，千万不要覆盖，要手工合并。
3. 修改config.php中的mail邮件配置，根据自己的邮箱来配置，如果不正确，则无法发送报警邮件。
4. 在database.php中加入 'debug'       => true,

5. 设置定时脚本
//每10分钟执行一次，将每个接口执行的sql写入对应的接口日志表
*/10 * * * * /usr/bin/php /www/web/项目目录/public/index.php index/monitor/logSqlToDb

//每分钟执行一次队列里的任务
*/1  * * * * /usr/bin/php /www/web/项目目录/public/index.php index/monitor/startQueue

# 使用方法

1. 修改application\index\behavior\Trace.php中 actionBegin()方法。里面可配置要屏蔽的url,以及定制一此要记录的信息。

2. 增加队列，异步执行方法 addQueue(模块名，控制器名,方法名,参数)，这样会异步执行你需要的方法


# 效果预览
![请求日志](https://github.com/caoygx/ThinkphpLogAndErrorAlarm/raw/master/assets/request_log.png)
![报警队列](https://github.com/caoygx/ThinkphpLogAndErrorAlarm/raw/master/assets/alarm_queue.png)
![报警邮件](https://github.com/caoygx/ThinkphpLogAndErrorAlarm/raw/master/assets/alarm_email.png)