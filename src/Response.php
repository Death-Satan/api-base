<?php

namespace DeathSatan\ApiBase;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * 原始Response类
     * @var ResponseInterface $response
     */
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * 获取原始数据
     * @return string
     */
    public function raw():string
    {
        return (string)$this->response->getBody();
    }

    /**
     * 获取原始response
     * @return ResponseInterface
     */
    public function response(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * 获取返回结果中的header
     * @param string|null $key
     * @return string[]|string[][]|null
     */
    public function header(?string $key=null): ?array
    {
        if (empty($key)){
            return  $this->response->getHeaders();
        }

        if ($this->response->hasHeader($key)){
            return $this->response->getHeader($key);
        }

        return null;
    }

    /**
     * 获取返回结果的状态码
     * @return int
     */
    public function status():int
    {
        return $this->response->getStatusCode();
    }

    /**
     * 将返回结果转换为数组
     * 返回结果必须是json类型字符串
     * @return array
     */
    public function toArray():array
    {
        $str = $this->raw();
        $arr = json_decode($str,true);
        if (json_last_error()!==JSON_ERROR_NONE)
        {
            return ['error'=>json_last_error_msg(),'errno'=>json_last_error()];
        }
        return $arr;
    }
}