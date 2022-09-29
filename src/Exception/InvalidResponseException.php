<?php

declare(strict_types=1);

/*
 * This file is part of the Transaction.Cloud PHP SDK.
 * Copyright Humbly Arrogant Ltd 2022
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TransactionCloud\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class InvalidResponseException extends \Exception
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response, $message = 'Invalid response from Transaction.cloud', $code = 0, Throwable $previous = null)
    {
        $this->response = $response;
        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
