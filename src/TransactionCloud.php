<?php

declare(strict_types=1);

/*
 * This file is part of the Transaction.Cloud PHP SDK.
 * Copyright Humbly Arrogant Ltd 2022
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TransactionCloud;

use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TransactionCloud\Exception\InvalidResponseException;
use TransactionCloud\Exception\MalformedResponseException;
use TransactionCloud\Exception\MissingModelDataException;
use TransactionCloud\Model\ModelFactory;
use TransactionCloud\Model\Product;
use TransactionCloud\Model\ProductData;
use TransactionCloud\Model\Refund;
use TransactionCloud\Model\Transaction;

final class TransactionCloud
{
    public const VERSION = 0.1;
    public const PROD_API_HOST = 'https://api.transction.cloud';
    public const SANDBOX_API_HOST = 'https://sandbox-api.transaction.cloud';
    public const PROD_HOSTED_HOST = 'https://hosted.transaction.cloud';
    public const SANDBOX_HOSTED_HOST = 'https://sandbox-hosted.transaction.cloud';

    private PsrClientInterface $client;
    private PsrRequestFactoryInterface $requestFactory;
    private ModelFactory $modelFactory;
    private StreamFactoryInterface $streamFactory;
    private string $apiBaseUrl;
    private string $hostedBaseUrl;

    public function __construct(?PsrClientInterface $client = null, ?PsrRequestFactoryInterface $requestFactory = null, ?StreamFactoryInterface $streamFactory, ?ModelFactory $factory = null, bool $sandbox = false)
    {
        $this->client = $client ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->apiBaseUrl = $sandbox ? self::SANDBOX_API_HOST : self::PROD_API_HOST;
        $this->hostedBaseUrl = $sandbox ? self::SANDBOX_HOSTED_HOST : self::PROD_HOSTED_HOST;
        $this->modelFactory = $factory ?? new ModelFactory();
    }

    public static function create(string $apiKey, string $apiKeyPassword, bool $sandbox = false): self
    {
        $defaultHeaders = new HeaderDefaultsPlugin([
            'User-Agent' => 'parthenon/transaction-cloud '.self::VERSION,
            'Authorization' => sprintf('%s:%s', $apiKey, $apiKeyPassword),
        ]);
        $pluginClient = new PluginClient(Psr18ClientDiscovery::find(), [$defaultHeaders]);

        return new self($pluginClient, Psr17FactoryDiscovery::findRequestFactory(), Psr17FactoryDiscovery::findStreamFactory(), new ModelFactory(), $sandbox);
    }

    public function getUrlToManageTransactions(string $email): string
    {
        $request = $this->requestFactory->createRequest('GET', sprintf('%s/v1/generate-url-to-manage-transactions/%s', $this->apiBaseUrl, $email));

        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents() ?? '', true);

        if (!$jsonData || !isset($jsonData['url']) || !is_string($jsonData['url'])) {
            throw new MalformedResponseException($response, 'Expected return body to contain a url key with a string value');
        }

        return $jsonData['url'];
    }

    public function getUrlToAdmin(): string
    {
        $request = $this->requestFactory->createRequest('GET', sprintf('%s/v1/generate-url-to-admin', $this->apiBaseUrl));

        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents() ?? '', true);

        if (!$jsonData || !isset($jsonData['url']) || !is_string($jsonData['url'])) {
            throw new MalformedResponseException($response, 'Expected return body to contain a url key with a string value');
        }

        return $jsonData['url'];
    }

    /**
     * @return Transaction[]
     *
     * @throws InvalidResponseException
     * @throws MalformedResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getTransactionsByEmail(string $email): array
    {
        $request = $this->requestFactory->createRequest('GET', sprintf('%s/v1/transactions/%s', $this->apiBaseUrl, $email));

        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents() ?? '', true);

        if (null === $jsonData) {
            throw new MalformedResponseException($response, 'Expected return body to contain valid json');
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
     * @throws InvalidResponseException
     * @throws MalformedResponseException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getTransactionById(string $transactionId): Transaction
    {
        $request = $this->requestFactory->createRequest('GET', sprintf('%s/v1/transaction/%s', $this->apiBaseUrl, $transactionId));
        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (null === $jsonData) {
            throw new MalformedResponseException($response, 'Expected return body to contain valid json');
        }

        try {
            return $this->modelFactory->buildTransaction($jsonData);
        } catch (MissingModelDataException $e) {
            throw new MalformedResponseException($response, $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function assignTransactionToEmail(string $transactionId, string $email): bool
    {
        $request = $this->requestFactory->createRequest('POST', sprintf('%s/v1/transaction/%s', $this->apiBaseUrl, $transactionId));
        $request->withBody($this->streamFactory->createStream(json_encode(['assignEmail' => $email])));
        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        return true;
    }

    public function cancelSubscription(string $transactionId): bool
    {
        $request = $this->requestFactory->createRequest('POST', sprintf('%s/v1/cancel-subscription/%s', $this->apiBaseUrl, $transactionId));
        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        return true;
    }

    public function refundTransaction(string $transactionId): Refund
    {
        $request = $this->requestFactory->createRequest('POST', sprintf('%s/v1/refund-transaction/%s', $this->apiBaseUrl, $transactionId));
        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (null === $jsonData) {
            throw new MalformedResponseException($response, 'Expected return body to contain valid json');
        }

        try {
            return $this->modelFactory->buildRefund($jsonData);
        } catch (MissingModelDataException $e) {
            throw new MalformedResponseException($response, $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function fetchChangedTransactions(): array
    {
        $request = $this->requestFactory->createRequest('GET', sprintf('%s/v1/changed-transactions', $this->apiBaseUrl));

        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (null === $jsonData) {
            throw new MalformedResponseException($response, 'Expected return body to contain valid json');
        }

        $output = [];
        try {
            foreach ($jsonData as $row) {
                $output[] = $this->modelFactory->buildChangedTransaction($row);
            }
        } catch (MissingModelDataException $e) {
            throw new MalformedResponseException($response, $e->getMessage(), $e->getCode(), $e);
        }

        return $output;
    }

    public function markTransactionAsProcessed(string $transactionId): bool
    {
        $request = $this->requestFactory->createRequest('POST', sprintf('%s/v1/changed-transactions/%s', $this->apiBaseUrl, $transactionId));
        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            return false;
        }

        return true;
    }

    public function customizeProduct(string $productId, Product $product): ProductData
    {
        $request = $this->requestFactory->createRequest('POST', sprintf('%s/v1/transaction/%s', $this->apiBaseUrl, $productId));
        $request->withBody($this->streamFactory->createStream(json_encode($product->getApiPayload())));
        $response = $this->client->sendRequest($request);

        if (200 !== $response->getStatusCode()) {
            throw new InvalidResponseException($response);
        }

        $jsonData = json_decode($response->getBody()->getContents(), true);

        if (null === $jsonData) {
            throw new MalformedResponseException($response, 'Expected return body to contain valid json');
        }

        try {
            return $this->modelFactory->buildProductData($jsonData);
        } catch (MissingModelDataException $e) {
            throw new MalformedResponseException($response, $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getPaymentUrlForProduct(string $productId): string
    {
        return sprintf('%s/payment/product/%s', $this->hostedBaseUrl, $productId);
    }
}
