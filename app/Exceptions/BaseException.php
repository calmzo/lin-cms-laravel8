<?php

namespace App\Exceptions;

//use Exception;

class BaseException extends \Exception
{
    //HTTP状态码
    public $code = 400;

    //错误具体信息
    public $msg = '参数错误';

    //自定义的错误码
    public $error_code = 10000;

    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return;
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('error_code', $params)) {
            $this->error_code = $params['error_code'];
        }
    }

    /**
     * @return mixed|string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param mixed|string $msg
     */
    public function setMsg($msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return int|mixed
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * @param int|mixed $error_code
     */
    public function setErrorCode($error_code): void
    {
        $this->error_code = $error_code;
    }


}
