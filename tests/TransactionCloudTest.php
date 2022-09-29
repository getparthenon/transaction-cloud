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
use TransactionCloud\Model\ChangedTransaction;
use TransactionCloud\Model\ModelFactory;
use TransactionCloud\Model\Refund;
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

    public function testCancelSuccess()
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

        $requestFactory->method('createRequest')->with("POST", sprintf("%s/v1/cancel-subscription/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);

        $this->assertTrue($subject->cancelSubscription($id));
    }

    public function testCancelSubscriptionVoid()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $id = "TC-TR_023003";

        $requestFactory->method('createRequest')->with("POST", sprintf("%s/v1/cancel-subscription/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(301);


        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);

        $this->assertFalse($subject->cancelSubscription($id));
    }

    public function testRefundTransaction()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $refund = $this->createMock(Refund::class);
        $id = "TC-TR_040405";

        $requestFactory->method('createRequest')->with("POST", sprintf("%s/v1/refund-transaction/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);


        $returnData =     [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => "US",
        ];

        $stream->method('getContents')->willReturn(json_encode($returnData));

        $modelFactory->method('buildRefund')->willReturn($refund);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);
        $actual = $subject->refundTransaction($id);

        $this->assertSame($refund, $actual);
    }

    public function testRefundTransactionInvalidStatus()
    {
        $this->expectException(InvalidResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $refund = $this->createMock(Refund::class);
        $id = "TC-TR_040405";

        $requestFactory->method('createRequest')->with("POST", sprintf("%s/v1/refund-transaction/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(301);
        $response->method('getBody')->willReturn($stream);


        $returnData =     [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => "US",
        ];

        $stream->method('getContents')->willReturn(json_encode($returnData));

        $modelFactory->expects($this->never())->method('buildRefund')->willReturn($refund);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);
        $subject->refundTransaction($id);
    }

    public function testRefundTransactionMalformResponse()
    {
        $this->expectException(MalformedResponseException::class);

        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $refund = $this->createMock(Refund::class);
        $id = "TC-TR_040405";

        $requestFactory->method('createRequest')->with("POST", sprintf("%s/v1/refund-transaction/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);


        $returnData =     [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => "US",
        ];

        $stream->method('getContents')->willReturn(json_encode($returnData));

        $modelFactory->expects($this->once())->method('buildRefund')->willThrowException(new MissingModelDataException());

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);
        $subject->refundTransaction($id);
    }

    public function testFetchChangedTransaction()
    {
        $client = $this->createMock(ClientInterface::class);
        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);
        $modelFactory = $this->createMock(ModelFactory::class);
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $changedTransaction = $this->createMock(ChangedTransaction::class);

        $requestFactory->method('createRequest')->with("GET", sprintf("%s/v1/changed-transactions", TransactionCloud::PROD_API_HOST))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);


        $returnData =     [
            [
                'createDate' => '2000-03-23',
                'lastCharge' => '2001-05-23',
                'nextCharge' => '2001-06-23',
                'assignedEmail' => '',
                'changedStatus' => 'CHANGED_STATUS_NEW',
                'chargeFrequency' => 'WEEKLY',
                'country' => 'US',
                'email' => 'iain.cambridge@example.org',
                'id' => 'TC-PR_zzzyyxx',
                'payload' => null,
                'productId' => 'TC-PR_dskfjsdl',
                'productName' => 'Product Name',
                'transactionStatus' => 'SUBSCRIPTION_STATUS_ACTIVE',
                'transactionType' => 'SUBSCRIPTION',
            ]
        ];

        $stream->method('getContents')->willReturn(json_encode($returnData));

        $modelFactory->expects($this->once())->method('buildChangedTransaction')->with($returnData[0])->willReturn($changedTransaction);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);
        $actual = $subject->fetchChangedTransactions();

        $this->assertCount(1, $actual);
        $this->assertEquals($changedTransaction, $actual[0]);
    }

    public function testMarkTransactionAsProcessSuccess()
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

        $requestFactory->method('createRequest')->with("POST", sprintf("%s/v1/changed-transactions/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(200);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);

        $this->assertTrue($subject->markTransactionAsProcessed($id));
    }

    public function testMarkTransactionAsProcessFailed()
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

        $requestFactory->method('createRequest')->with("POST", sprintf("%s/v1/changed-transactions/%s", TransactionCloud::PROD_API_HOST, $id))->willReturn($request);
        $client->method('sendRequest')->with($request)->willReturn($response);

        $response->method('getStatusCode')->willReturn(401);

        $subject = new TransactionCloud($client, $requestFactory, $streamFactory, $modelFactory);

        $this->assertFalse($subject->markTransactionAsProcessed($id));
    }
}