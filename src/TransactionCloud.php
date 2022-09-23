<?php

namespace TransactionCloud;

use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use TransactionCloud\Exception\InvalidResponseException;
use TransactionCloud\Exception\MalformedResponseException;

final class TransactionCloud implements ClientInterface
{
    public const VERSION = 0.1;
    public const PROD_API_HOST = "https://api.transction.cloud";
    public const SANDBOX_API_HOST = "https://sandbox-api.transaction.cloud";

    private PsrClientInterface $client;
    private PsrRequestFactoryInterface $requestFactory;
    private string $baseUrl;

    public function __construct(?PsrClientInterface $client = null, ?PsrRequestFactoryInterface $requestFactory = null, ?string $baseUrl = null) {
        $this->client = $client ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->baseUrl = $baseUrl ?? self::PROD_API_HOST;
    }

    public static function create(string $apiKey, string $apiKeyPassword, bool $sandbox = false): self {

        $defaultHeaders = new HeaderDefaultsPlugin([
            'User-Agent' => 'parthenon/transaction-cloud '.self::VERSION,
            'Authorization' => sprintf("%s:%s", $apiKey, $apiKeyPassword),
        ]);
        $pluginClient = new PluginClient(Psr18ClientDiscovery::find(), [$defaultHeaders]);

        return new self($pluginClient, Psr17FactoryDiscovery::findRequestFactory(), $sandbox ? self::SANDBOX_API_HOST : self::PROD_API_HOST);
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
}