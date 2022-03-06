<?php

namespace DeathSatan\ApiBase\Contracts;

use Psr\Http\Message\ResponseInterface;

interface ToBase
{
    public function __construct(ResponseInterface  $stream);

    public function result();
}