<?php

namespace TransactionCloud\Model;

use Brick\Money\Money;

class Product
{
    /**
     * @var Money[]
     */
    private array $prices = [];

    private string $description = "";

    private string $payload = "";

    private string $transactionIdToMigrate = "";

    public function __construct(array $prices = [], string $description = "", string $payload = "", string $transactionIdToMigrate = "")
    {
        $this->prices = $prices;
        $this->description = $description;
        $this->payload = $payload;
        $this->transactionIdToMigrate = $transactionIdToMigrate;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function setPrices(array $prices): void
    {
        $this->prices = $prices;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    public function getTransactionIdToMigrate(): string
    {
        return $this->transactionIdToMigrate;
    }

    public function setTransactionIdToMigrate(string $transactionIdToMigrate): void
    {
        $this->transactionIdToMigrate = $transactionIdToMigrate;
    }

    public function getApiPayload() : array
    {
        $prices = [];

        foreach ($this->prices as $price) {
            $prices[] = [
                'currency' => $price->getCurrency()->getCurrencyCode(),
                'value' => $price->getAmount()->toFloat(),
            ];
        }

        return [
            'prices' => $prices,
            'description' => $this->description,
            'payload' => $this->payload,
            'transactionIdToMigrate' => $this->transactionIdToMigrate,
        ];
    }
}