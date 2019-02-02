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
];
  