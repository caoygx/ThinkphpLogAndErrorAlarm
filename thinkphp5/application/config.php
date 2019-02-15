<?php
return
    [
    'exception_handle'       => '\\rest\\common\\exception\\Notification',
    'log'                    => [
        'type'  => 'File',
        'path'  => LOG_PATH,
        'level' => ['error','sql'],
        'file_size'=>102400000,
    ],
    'enable_log_request'=>true, //记录请求日志
	 'mail'=>[
        'username'=>'123456@qq.com',
        'password'=>'123456',
        'host'=>'smtp.qq.com',
        'port'=>465,
    ],
];
  