<?php

namespace DeathSatan\ApiBase;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Request
{
    /**
     * http请求客户端
     * @var Client $client
     */
    protected $client;

    /**
     * @var Closure[]
     */
    protected static $after_closure = [];

    /**
     * @var Closure[]
     */
    protected static $before_closure = [];

    /**
     * @var Closure[]
     */
    protected static $before_client_closure = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
        foreach (static::$before_client_closure as $closure)
        {
            $closure($this->client);
        }
    }

    /**
     * 设置一个前置处理httpClient函数
     * @param Closure $before_client_closure
     */
    public static function setBeforeClientClosure(Closure $before_client_closure): void
    {
        self::$before_client_closure[] = $before_client_closure;
    }

    /**
     * 前置处理参数方法
     * @param string $type
     * @param string $uri
     * @param array $option
     * @return array
     */
    protected function withRequest(string $type,string &$uri,array &$option):array
    {
        foreach (static::$before_closure as $closure)
        {
            [$uri,$option] = $closure($type,$uri,$option);
        }
        return [$uri,$option];
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * 后置处理
     * @param ResponseInterface $response
     * @return Response
     */
    protected function withResponse(ResponseInterface $response):Response
    {
        $new_response = new Response($response);
        foreach (static::$after_closure as $closure)
        {
            $closure($new_response);
        }
        return $new_response;
    }

    /**
     * 发送get请求
     * @param string $uri url
     * @param array $params 参数
     * @throws GuzzleException
     */
    public function get(string $uri, array $params): Response
    {
        $option['query'] = $params;
        [$uri,$option] = $this->withRequest(__FUNCTION__,$uri,$option);
        return $this->withResponse($this->client->get($uri,$option));
    }

    /**
     * 发送一个post json请求
     * @param string $uri url
     * @param array $params 参数
     * @return Response
     * @throws GuzzleException
     */
    public function postJson(string $uri,array $params):Response
    {
        $option['json'] = $params;
        [$uri,$option] = $this->withRequest(__FUNCTION__,$uri,$option);
        return $this->withResponse(
            $this->client->request('POST',$uri,$option)
        );
    }

    /**
     * 发送post请求
     * @param string $uri url
     * @param array $params 参数
     * @throws GuzzleException
     */
    public function post(string $uri, array $params): Response
    {
        $option['form_params'] = $params;
        [$uri,$option] = $this->withRequest(__FUNCTION__,$uri,$option);
        return $this->withResponse($this->client->post($uri,$option));
    }

    /**
     * 设置一个后缀请求闭包函数
     * @param Closure|null $after_closure
     */
    public static function setAfterClosure(Closure $after_closure): void
    {
        static::$after_closure[] = $after_closure;
    }

    /**
     * 设置一个请求前置闭包
     * @param Closure $before_closure
     */
    public static function setBeforeClosure(Closure $before_closure): void
    {
        self::$before_closure[] = $before_closure;
    }
}