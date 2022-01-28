<?php

namespace DeathSatan\ApiBase;

use GuzzleHttp\Client;

class Application
{
    /**
     * 配置函数
     * @var array $config
     */
    protected $config = [];

    /**
     * http客户端
     * @var null|Client
     */
    protected $client;

    /**
     * 创建一个应用程序
     * @param array $config 配置项
     * 如果要配置guzzleHttp的选项
     * 示例: ['http'=>[
     *      'verify'=>false,//关闭ssl验证
     *      'timeout'=>5.0,//超时设置5秒
     * ]]
     */
    public function __construct(array $config=[])
    {
        $this->config = $config;
        $this->client = new Client(
            empty($config['http'])?[]:$config['http']
        );
    }

    public static function make(array $config=[]): self
    {
        return new static($config);
    }
}