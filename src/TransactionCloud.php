<?php

namespace TransactionCloud;

use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface as PsrClientInterface;

class TransactionCloud implements ClientInterface
{
    private PsrClientInterface $client;

    public function __construct(string $username, string $password, ?PsrClientInterface $client = null) {
        $this->client = $client ?? Psr18ClientDiscovery::find();
    }

    public static function create(string $username, string $password): self {
        return new self($username, $password, Psr18ClientDiscovery::find());
    }
}