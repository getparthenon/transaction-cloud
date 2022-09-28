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

    public function testGetAdminUrl404()
    {
        $this->expectException(InvalidResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(403);

        $subject = new TransactionCloud($client, $requestFactory);
        $subject->getUrlToAdmin();
    }

    public function testGetAdminUrlInvalidContentNull()
    {
        $this->expectException(MalformedResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $subject = new TransactionCloud($client, $requestFactory);
        $subject->getUrlToAdmin();
    }

    public function testGetAdminUrlInvalidContentNoUrl()
    {
        $this->expectException(MalformedResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode([]));

        $subject = new TransactionCloud($client, $requestFactory);
        $subject->getUrlToAdmin();
    }

    public function testGetAdminUrlInvalidContentUrlIsNotString()
    {
        $this->expectException(MalformedResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode(['url' => 1]));

        $subject = new TransactionCloud($client, $requestFactory);
        $subject->getUrlToAdmin();
    }

    public function testGetAdminUrlReturnsUrl()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $email = "iain.cambridge@example.com";
        $url = "http://manage.example.org/admin-url";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode(['url' => $url]));

        $subject = new TransactionCloud($client, $requestFactory);
        $actual = $subject->getUrlToAdmin();

        $this->assertEquals($url, $actual);
    }

    public function testGetTransactionsByEmail()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);


        $returnData = [
            [
                "assignedEmail" => "",
        "chargeFrequency"=> "UNKNOWN",
        "country"=> "US",
        "createDate" => "2021-09-20",
        "email" => "customer@domain.com",
        "id" => "TC-TR_xxyyxxx",
        "lastCharge" => "2021-09-20",
        "payload" => "",
        "productId" => "TC-PR_qqqzzzyy",
        "productName" =>  "Product name",
        "transactionStatus" => "ONE_TIME_PAYMENT_STATUS_PAID",
        "transactionType" => "ONETIME",
        "netPrice" => "20.0",
        "tax" => "2.4",
        "currency" => "USD",
        "netPriceInUSD" => 20.0
    ]
        ];

        $stream->method('getContents')->willReturn(json_encode($returnData));

        $subject = new TransactionCloud($client, $requestFactory);
        $actual = $subject->getTransactionsByEmail($email);

        $this->assertCount(1, $actual);
    }
}