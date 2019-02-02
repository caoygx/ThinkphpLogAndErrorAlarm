<?php

namespace application\index\behavior;
class Trace
{
    function actionBegin(){

        if(!config('enable_log_request') || IS_CLI)	return;

       try {
            //error_reporting(E_ERROR | E_PARSE);
            //error_reporting(E_ALL);
            defined('IS_POST') or define('IS_POST', request()->isPost());
            defined('IS_AJAX') or define('IS_AJAX',request()->isAjax());
            defined('IS_GET') or define('IS_GET', request()->isGet());
            
			//不需要记录的url地址
			$blacklist = [
                "/index/news/getList",
            ];

            foreach ($blacklist as $v) {
                if (strpos($_SERVER['REQUEST_URI'], $v) !== false) return;
            }

            $data = array();
            $data['url'] = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            if (IS_POST) {
                $params = $_POST;
            } elseif (IS_GET) {
                $params = $_GET;
            }
            if (empty($params)) $params['input'] = file_get_contents("php://input");
            $data['params'] = json_encode($params);
            //$data['cookie'] = json_encode($_COOKIE);
            //$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $data['ip'] = get_client_ip();
            $detail = array();
            $detail['request'] = $_REQUEST;

            $header = [];
            $fields = ['HTTP_USER_ID', 'HTTP_DEVICE_VID', 'HTTP_DEVICE_ID', 'HTTP_PLATFORM', 'HTTP_VERSION']; //'HTTP_USER_AGENT',
            foreach ($fields as $k => $v) {
                if (empty($_SERVER[$v])) continue;
                $header[$v] = $_SERVER[$v];
            }
            /*$this->version = I('server.HTTP_VERSION');
            $this->device_id = I('device_id') ?:I('server.HTTP_DEVICE_ID');
            $this->platform = I('server.HTTP_PLATFORM');
            $user_id = I('user_id') ?: I('server.HTTP_USER_ID');
            $detail['server'] = $_SERVER;*/
            //$detail['header'] = $header;
            //$data['detail'] = json_encode($detail);
            $url = $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . " " . $_SERVER['SERVER_PROTOCOL'] . "\r\n";
            $request = $url . getallheaders(true);

            $raw_post = '';
            if (IS_POST) {
                $raw_post = http_build_query($_POST);
                if (empty($raw_post)) {
                    $raw_post = file_get_contents("php://input");
                }
            }
            $request .= "\r\n" . $raw_post;

            $data['detail'] = $request;
            $data['user_agent'] = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
            //$data['platform'] = I('server.HTTP_PLATFORM');
            $data['user_id'] = I('user_id');////cookie可能取出null,要求字段必须可为null
            if (empty($data['user_id'])) {
                $userInfo = cookie('LOGIN_USER');
                $user_id = $userInfo['user_id'];
                $data['user_id'] = $user_id;
            }

            $data['create_time'] = date("Y-m-d H:i:s");
            $data['method'] = $_SERVER['REQUEST_METHOD'];
            //$data['date_int'] = time();


            $m = db('LogRequest');
            //$m->create($data);
            $result = $m->insert($data);
            $logId = $m->getLastInsID();
            config('logId', $logId);
        }catch (\Exception $e){
            tplog($e->getMessage());
        }

        \think\Db::listen(function($sql, $time, $explain){

            $logId = config('logId');
            if(!empty($logId) && strpos($sql,'log_request') === false){
                file_put_contents(RUNTIME_PATH."/$logId.sql", $sql.PHP_EOL, FILE_APPEND);
            }

            // 记录SQL
            //echo $sql. ' ['.$time.'s]';
            // 查看性能分析结果
            //dump($explain);
        });
        // exit('x');

    }
}