<?php

namespace Tests\TransactionCloud;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use TransactionCloud\Exception\InvalidResponseException;
use TransactionCloud\Exception\MalformedResponseException;
use TransactionCloud\TransactionCloud;

class TransactionCloudTest extends TestCase
{
    public function testCreateReturnsSelfInstance()
    {
        $actual = TransactionCloud::create("username", "password");

        $this->assertInstanceOf(TransactionCloud::class, $actual);
    }

    public function testGetManageTransactionsUrl404()
    {
        $this->expectException(InvalidResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(403);

        $subject = new TransactionCloud($client, $requestFactory);
        $subject->getUrlToManageTransactions($email);
    }

    public function testGetManageTransactionsUrlInvalidContentNull()
    {
        $this->expectException(MalformedResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $subject = new TransactionCloud($client, $requestFactory);
        $subject->getUrlToManageTransactions($email);
    }

    public function testGetManageTransactionsUrlInvalidContentNoUrl()
    {
        $this->expectException(MalformedResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode([]));

        $subject = new TransactionCloud($client, $requestFactory);
        $subject->getUrlToManageTransactions($email);
    }

    public function testGetManageTransactionsUrlInvalidContentUrlIsNotString()
    {
        $this->expectException(MalformedResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode(['url' => 1]));

        $subject = new TransactionCloud($client, $requestFactory);
        $subject->getUrlToManageTransactions($email);
    }

    public function testGetManageTransactionsUrlReturnsUrl()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $email = "iain.cambridge@example.com";
        $url = "http://manage.example.org/url";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode(['url' => $url]));

        $subject = new TransactionCloud($client, $requestFactory);
        $actual = $subject->getUrlToManageTransactions($email);

        $this->assertEquals($url, $actual);
    }
}