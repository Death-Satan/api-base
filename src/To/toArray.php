<?php

namespace DeathSatan\ApiBase\To;

use DeathSatan\ApiBase\Exceptions\ConvertException;
use DeathSatan\ApiBase\Traits\ToBase;
use Psr\Http\Message\ResponseInterface;

class toArray implements \DeathSatan\ApiBase\Contracts\ToBase
{
    use ToBase;

    public static $JSON_DECODE = JSON_UNESCAPED_UNICODE;

    /**
     * @throws ConvertException
     */
    public function result():array
    {
        $body = $this->raw();
        $data = json_decode($body,JSON_UNESCAPED_UNICODE);
        if (json_last_error()===JSON_ERROR_NONE){
            return $data;
        }else{
            throw new ConvertException(json_last_error_msg());
        }
    }

    /**
     * @param int $JSON_DECODE
     */
    public static function setJSONDECODE(int $JSON_DECODE): void
    {
        self::$JSON_DECODE = $JSON_DECODE;
    }

}