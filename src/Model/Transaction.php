<?php

namespace TransactionCloud\Model;

class Transaction
{
    private string $assignedEmail;
    private string $chargeFrequency;
    private string $country;
    private \DateTimeInterface $createDate;
    private string $email;
    private string $id;
    private \DateTimeInterface $lastCharge;
    private ?string $payload;
    private string $productId;
    private string $productName;
    private string $transactionStatus;
    private string $transactionType;
    private string $netPrice;
    private string $tax;
    private string $currency;

    public function __construct(
        string $assignedEmail,
        string $chargeFrequency,
        string $country,
        \DateTimeInterface $createDate,
        string $email,
        string $id,
        \DateTimeInterface $lastCharge,
        ?string $payload,
        string $productId,
        string $productName,
        string $transactionStatus,
        string $transactionType,
        string $netPrice,
        string $tax,
        string $currency
    ) {
        $this->assignedEmail = $assignedEmail;
        $this->chargeFrequency = $chargeFrequency;
        $this->country = $country;
        $this->createDate = $createDate;
        $this->email = $email;
        $this->id = $id;
        $this->lastCharge = $lastCharge;
        $this->payload = $payload;
        $this->productId = $productId;
        $this->productName = $productName;
        $this->transactionStatus = $transactionStatus;
        $this->transactionType = $transactionType;
        $this->netPrice = $netPrice;
        $this->tax = $tax;
        $this->currency = $currency;
    }

    public function getAssignedEmail(): string
    {
        return $this->assignedEmail;
    }

    public function getChargeFrequency(): string
    {
        return $this->chargeFrequency;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCreateDate(): \DateTimeInterface
    {
        return $this->createDate;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLastCharge(): \DateTimeInterface
    {
        return $this->lastCharge;
    }

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getTransactionStatus(): string
    {
        return $this->transactionStatus;
    }

    public function getTransactionType(): string
    {
        return $this->transactionType;
    }

    public function getNetPrice(): string
    {
        return $this->netPrice;
    }

    public function getTax(): string
    {
        return $this->tax;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}