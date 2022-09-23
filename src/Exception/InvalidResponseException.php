<?php

namespace TransactionCloud\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class InvalidResponseException extends \Exception
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response, $message = "Invalid response from Transaction.cloud", $code = 0, Throwable $previous = null)
    {
        $this->response = $response;
        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}