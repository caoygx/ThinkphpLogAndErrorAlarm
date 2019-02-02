<?php

//使用smtp
function sendmail($subject,$body,$to,$toname,$from = "",$fromname = '项目名称',$altbody = '邮件',$wordwrap = 80,$mailconf = ''){

    $mail             = new \PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->SMTPDebug  = 0;                   // enables SMTP debug // 1 = errors and messages// 2 = messages only
    $mail->Timeout = 3;

    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Host       = config('mail.host'); // sets the SMTP server
    $mail->Port       = config('mail.port');                    // set the SMTP port for the GMAIL server
    $mail->Username   = config("mail.username"); // SMTP account username
    $mail->Password   = config('mail.password');        // SMTP account password

    $from = config("mail.username");
    $mail->SetFrom($from, $fromname);

    //$mail->AddReplyTo($to,$toname);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->SMTPSecure = "ssl"; // SMTP 安全协议
    $mail->Subject    = $subject;
    //$body             = eregi_replace("[\]",'',$body);
    //$mail->AltBody    = "AltBody"; // optional, comment out and test

    $mail->MsgHTML($body);


    if(is_array($to)){
        foreach($to as $v){
            $mail->AddAddress($v, $toname);
        }
    }else{
        $mail->AddAddress($to, $toname);
    }


    //$mail->AddAttachment("images/phpmailer.gif");      // attachment
    //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    } else {
        return true;
    }
}

function error_notify($error_content){
    $uri = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    $error_content = $uri."<br />".$error_content;
    alarm($error_content);
    //$admin_mail = C('admin_eamil');
    //if($admin_mail) $r = sendmail("出错啦！",$error_content,$admin_mail,'');
}

/**
 * 增加队列消息 各controller只有删除自己的队列
 * @param string $module
 * @param string $controller
 * @param string $action
 * @param array $parameter
 */
function addQueue(string $module,string $controller,string $action,array $parameter){
    //加入访问历史
    $qMsg = [];
    $qMsg['module'] = $module;
    $qMsg['controller'] = $controller;
    $qMsg['action'] = $action;
    $qMsg['parameter'] = $parameter;
    $qMsg = json_encode($qMsg);
    $data["message"] = $qMsg;
    \think\Db::name("queue")->insert($data);
}

/**
 * To send alarm when program error
 * @param $content
 */
function alarm($content){
    $qHistoryMsgParameter = [
        "content" => $content,
    ];
    addQueue("Index","monitor","alarm",$qHistoryMsgParameter);
}