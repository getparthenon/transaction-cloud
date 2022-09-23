<?php

namespace Tests\TransactionCloud;

use PHPUnit\Framework\TestCase;
use TransactionCloud\TransactionCloud;

class TransactionCloudTest extends TestCase
{
    public function testCreateReturnsSelfInstance()
    {
        $actual = TransactionCloud::create("username", "password");

        $this->assertInstanceOf(TransactionCloud::class, $actual);
    }
}