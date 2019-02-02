<?php
namespace rest\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\HttpException;
class Notification extends Handle
{

    public function render(Exception $e)
    {
      /*  // 参数验证错误
        if ($e instanceof ValidateException) {
            return json($e->getError(), 422);
        }

        // 请求异常
        if ($e instanceof HttpException && request()->isAjax()) {
            return response($e->getMessage(), $e->getStatusCode());
        }*/


        //TODO::开发者对异常的操作

        $errorContent = $e->getMessage().' '.$e->getFile().' '.$e->getLine().'行';
        //echo $errorContent;
        if(function_exists('error_notify'))  error_notify($errorContent);

        //可以在此交由系统处理
        return parent::render($e);
    }

}