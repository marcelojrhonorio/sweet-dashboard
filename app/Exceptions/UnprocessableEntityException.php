<?php

namespace App\Exceptions;

class UnprocessableEntityException extends \GuzzleHttp\Exception\ClientException
{
    protected $code = 422;
}
