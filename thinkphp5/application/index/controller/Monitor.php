<?php

namespace rest\index\controller;
use think\Controller;

class Monitor extends Controller
{
    public $m;



    public function startQueue()
    {
        $this->m = db('queue');

        $r = $this->m->select();
        foreach ($r as $k => $v) {
            $message = json_decode($v['message'],true);
            extract($message);
            if(empty($module)) $module = "User";
            //var_dump($parameter);
            //var_dump("{$module}/{$controller}/{$action}");
            $r = action("{$module}/{$controller}/{$action}",$parameter);
            if($r){
                $this->m->delete($v['id']);
            }
            //var_dump($r);
        }
    }
	
	    function logSqlToDb(){

        $files = glob(RUNTIME_PATH."*.sql");
        foreach ($files as $k=>$file){
            $pathinfo = pathinfo($file);
            $id = $pathinfo['filename'];
            if(!is_numeric($id)) continue;
            $sqlContent = file_get_contents($file);
            //$sqlContent = addslashes($sqlContent);
            $r = \think\Db::name('LogRequest')->where(['id'=>$id])->update(['response'=>$sqlContent]);
            //echo \think\Db::name('LogRequest')->getLastSql();
            if($r){
                unlink($file);
            }
        }
    }
	 
    /**
     * 用于处理队列
     * 必须定义接受参数，否则接受不到列表分发器传过来的参数
     * @param $content

     */
    function alarm(string $content){
        try{
            $admin_mail = C('admin_eamil');
            if($admin_mail){
                $r = sendmail("出错啦！",$content,$admin_mail,'');
                if($r) return true;
            }
        }catch (\Exception $e){
            tplog('邮件发送失败');
        }
        return false;
    }

    /**
     * 用于处理队列
     * 必须定义接受参数，否则接受不到列表分发器传过来的参数
     * @param $content

     */
    function alarm_sms(string $content){
        try{
            //发短信提示课程拥有者
            //$mobile = getMobileOfCourseOwner();
            $mobile = '13100001111';

            $content = cn_substr_utf8($content,40);
            $template_id = 'SMS_123';

            $d = [];
            $d['mobile'] = $mobile;
            $d['content'] = "content:{$content} ";
            $d['ip'] = get_client_ip();
            $sms_id = db('SmsQueue')->add($d);
            $r = send_sms_system($mobile, ['content'=>$content],$template_id,$sms_id);
        }catch (\Exception $e){
            tplog('新评论提示符短信发送失败');
        }
    }
}






