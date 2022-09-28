<?php

namespace Tests\TransactionCloud;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use TransactionCloud\Exception\InvalidResponseException;
use TransactionCloud\Exception\MalformedResponseException;
use TransactionCloud\Exception\MissingModelDataException;
use TransactionCloud\Model\ModelFactory;
use TransactionCloud\Model\Transaction;
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
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(403);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
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
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
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
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode([]));

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
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
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode(['url' => 1]));

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
        $subject->getUrlToManageTransactions($email);
    }

    public function testGetManageTransactionsUrlReturnsUrl()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.com";
        $url = "http://manage.example.org/url";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode(['url' => $url]));

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
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
        $streamFactory = $this->createMock(StreamFactoryInterface::class);

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(403);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
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
        $streamFactory = $this->createMock(StreamFactoryInterface::class);

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
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
        $streamFactory = $this->createMock(StreamFactoryInterface::class);

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode([]));

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
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
        $streamFactory = $this->createMock(StreamFactoryInterface::class);

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode(['url' => 1]));

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
        $subject->getUrlToAdmin();
    }

    public function testGetAdminUrlReturnsUrl()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.com";
        $url = "http://manage.example.org/admin-url";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/generate-url-to-admin", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $stream->method('getContents')->willReturn(json_encode(['url' => $url]));

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
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
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
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

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
        $actual = $subject->getTransactionsByEmail($email);

        $this->assertCount(1, $actual);
    }

    public function testGetTransactionsByEmailNoResults()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);


        $returnData = [
        ];

        $stream->method('getContents')->willReturn(json_encode([]));

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
        $actual = $subject->getTransactionsByEmail($email);

        $this->assertCount(0, $actual);
    }


    public function testGetTransactionsByEmailExceptionFlungOnInvalidResponse()
    {
        $this->expectException(MalformedResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);


        $stream->method('getContents')->willReturn(null);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
        $actual = $subject->getTransactionsByEmail($email);
    }

    public function testGetTransactionsByEmailExceptionFlungOnInvalidStatus()
    {
        $this->expectException(InvalidResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.com";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/transactions/%s", TransactionCloud::PROD_API_HOST, $email))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(301);
        $response->method('getBody')->willReturn($stream);


        $stream->method('getContents')->willReturn([]);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory);
        $actual = $subject->getTransactionsByEmail($email);
    }

    public function testGetTransactionsByEmailExceptionFlungOnFactoryException()
    {
        $this->expectException(InvalidResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
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

        $modelFactory->method('buildTransaction')->willThrowException(new MissingModelDataException());

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);
        $actual = $subject->getTransactionsByEmail($email);

        $this->assertCount(1, $actual);
    }

    public function testGetTransactionByIdValid()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $id = "TC-TR_040405";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/transaction/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);


        $returnData =     [
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
        ];

        $stream->method('getContents')->willReturn(json_encode($returnData));

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);
        $actual = $subject->getTransactionById($id);

        $this->assertInstanceOf(Transaction::class, $actual);
    }

    public function testGetTransactionByIdThrowException()
    {
        $this->expectException(MalformedResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $id = "TC-TR_040405";

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/transaction/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);


        $returnData =     [
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
        ];

        $stream->method('getContents')->willReturn(json_encode($returnData));

        $modelFactory->method('buildTransaction')->willThrowException(new MissingModelDataException());

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);
        $actual = $subject->getTransactionById($id);
    }

    public function testAssignEmailSuccess()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.org";
        $id = "TC-TR_023003";

        $requestFactory->method('createRequest')->with("POST", sprintf("%s/v1/transaction/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);

        $streamFactory->method('createStream')->with(json_encode(['assignEmail' => $email]))->willReturn($stream);
        $request->expects($this->once())->method("withBody")->with($stream);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);

        $this->assertTrue($subject->assignTransactionToEmail($id, $email));
    }

    public function testAssignEmail()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $email = "iain.cambridge@example.org";
        $id = "TC-TR_023003";

        $requestFactory->method('createRequest')->with("POST", sprintf("%s/v1/transaction/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(301);

        $streamFactory->method('createStream')->with(json_encode(['assignEmail' => $email]))->willReturn($stream);
        $request->expects($this->once())->method("withBody")->with($stream);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);

        $this->assertFalse($subject->assignTransactionToEmail($id, $email));
    }
}