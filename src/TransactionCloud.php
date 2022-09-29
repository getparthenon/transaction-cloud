<?php

namespace TransactionCloud;

use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\StreamFactory;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TransactionCloud\Exception\InvalidResponseException;
use TransactionCloud\Exception\MalformedResponseException;
use TransactionCloud\Exception\MissingModelDataException;
use TransactionCloud\Model\ModelFactory;
use TransactionCloud\Model\Refund;
use TransactionCloud\Model\Transaction;

final class TransactionCloud implements ClientInterface
{
    public const VERSION = 0.1;
    public const PROD_API_HOST = "https://api.transction.cloud";
    public const SANDBOX_API_HOST = "https://sandbox-api.transaction.cloud";

    private PsrClientInterface $client;
    private PsrRequestFactoryInterface $requestFactory;
    private ModelFactory $modelFactory;
    private StreamFactoryInterface $streamFactory;
    private string $baseUrl;

    public function __construct(?PsrClientInterface $client = null, ?PsrRequestFactoryInterface $requestFactory = null, ?StreamFactoryInterface $streamFactory, ?ModelFactory $factory = null, ?string $baseUrl = null) {
        $this->client = $client ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->baseUrl = $baseUrl ?? self::PROD_API_HOST;
        $this->modelFactory = $factory ?? new ModelFactory();
    }

    public static function create(string $apiKey, string $apiKeyPassword, bool $sandbox = false): self {

        $defaultHeaders = new HeaderDefaultsPlugin([
            'User-Agent' => 'parthenon/transaction-cloud '.self::VERSION,
            'Authorization' => sprintf("%s:%s", $apiKey, $apiKeyPassword),
        ]);
        $pluginClient = new PluginClient(Psr18ClientDiscovery::find(), [$defaultHeaders]);

        return new self($pluginClient, Psr17FactoryDiscovery::findRequestFactory(), Psr17FactoryDiscovery::findStreamFactory(), new ModelFactory(),$sandbox ? self::SANDBOX_API_HOST : self::PROD_API_HOST);
    }

    public function getUrlToManageTransactions(string $email) : string {
        $request = $this->requestFactory->createRequest("GET", sprintf("%s/v1/generate-url-to-manage-transactions/%s", $this->baseUrl, $email));

        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (!$jsonData || !isset($jsonData['url']) || !is_string($jsonData['url'])) {
            throw new MalformedResponseException($response, "Expected return body to contain a url key with a string value");
        }

        return $jsonData['url'];
    }

    public function getUrlToAdmin() : string {
        $request = $this->requestFactory->createRequest("GET", sprintf("%s/v1/generate-url-to-admin", $this->baseUrl));

        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (!$jsonData || !isset($jsonData['url']) || !is_string($jsonData['url'])) {
            throw new MalformedResponseException($response, "Expected return body to contain a url key with a string value");
        }

        return $jsonData['url'];
    }

    /**
     * @param string $email
     * @return Transaction[]
     * @throws InvalidResponseException
     * @throws MalformedResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getTransactionsByEmail(string $email): array
    {
        $request = $this->requestFactory->createRequest("GET", sprintf("%s/v1/transactions/%s", $this->baseUrl, $email));

        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if ($jsonData === null) {
            throw new MalformedResponseException($response, "Expected return body to contain valid json");
        }

        $output = [];
        try {
            foreach ($jsonData as $row) {
                $output[] = $this->modelFactory->buildTransaction($row);
            }
        } catch (MissingModelDataException $e) {
            throw new MalformedResponseException($response, $e->getMessage(), $e->getCode(), $e);
        }

        return $output;
    }

    /**
     * @param string $id
     * @return Transaction
     * @throws InvalidResponseException
     * @throws MalformedResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getTransactionById(string $id): Transaction
    {
        $request = $this->requestFactory->createRequest("GET", sprintf("%s/v1/transaction/%s", $this->baseUrl, $id));
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if ($jsonData === null) {
            throw new MalformedResponseException($response, "Expected return body to contain valid json");
        }

        try {
            return $this->modelFactory->buildTransaction($jsonData);
        } catch (MissingModelDataException $e) {
            throw new MalformedResponseException($response, $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function assignTransactionToEmail(string $id, string $email): bool
    {
        $request = $this->requestFactory->createRequest("POST", sprintf("%s/v1/transaction/%s", $this->baseUrl, $id));
        $request->withBody($this->streamFactory->createStream(json_encode(['assignEmail' => $email])));
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            return false;
        }
        return true;
    }

    public function cancelSubscription(string $id): bool
    {
        $request = $this->requestFactory->createRequest("POST", sprintf("%s/v1/cancel-subscription/%s", $this->baseUrl, $id));
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            return false;
        }
        return true;
    }

    public function refundTransaction(string $transactionId): Refund
    {
        $request = $this->requestFactory->createRequest("POST", sprintf("%s/v1/refund-transaction/%s", $this->baseUrl, $transactionId));
        $response = $this->client->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if ($jsonData === null) {
            throw new MalformedResponseException($response, "Expected return body to contain valid json");
        }

        try {
            return $this->modelFactory->buildRefund($jsonData);
        } catch (MissingModelDataException $e) {
            throw new MalformedResponseException($response, $e->getMessage(), $e->getCode(), $e);
        }
    }
}