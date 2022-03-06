<?php

namespace DeathSatan\ApiBase\Traits;

use Psr\Http\Message\ResponseInterface;

trait ToBase
{
    /**
     * @var ResponseInterface $stream
     */
    protected $response;

    public function __construct(ResponseInterface  $stream)
    {
        $this->response = $stream;
    }

    protected function raw(): \Psr\Http\Message\StreamInterface
    {
        return $this->response->getBody();
    }


}