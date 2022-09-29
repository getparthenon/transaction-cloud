<?php

namespace TransactionCloud\Model;

use Brick\Money\Currency;
use Brick\Money\Money;

class ChangedTransaction
{
    private string $assignedEmail;
    private string $changedStatus;
    private string $chargeFrequency;
    private string $country;
    private \DateTimeInterface $createDate;
    private string $email;
    private string $id;
    private \DateTimeInterface $lastCharge;
    private \DateTimeInterface $nextCharge;
    private ?string $payload;
    private string $productId;
    private string $productName;
    private string $transactionStatus;
    private string $transactionType;

    public function __construct(
        string $assignedEmail,
        string $changedStatus,
        string $chargeFrequency,
        string $country,
        \DateTimeInterface $createDate,
        string $email,
        string $id,
        \DateTimeInterface $lastCharge,
        \DateTimeInterface $nextCharge,
        ?string $payload,
        string $productId,
        string $productName,
        string $transactionStatus,
        string $transactionType
    )
    {
        $this->assignedEmail = $assignedEmail;
        $this->changedStatus = $changedStatus;
        $this->chargeFrequency = $chargeFrequency;
        $this->country = $country;
        $this->createDate = $createDate;
        $this->email = $email;
        $this->id = $id;
        $this->lastCharge = $lastCharge;
        $this->nextCharge = $nextCharge;
        $this->payload = $payload;
        $this->productId = $productId;
        $this->productName = $productName;
        $this->transactionStatus = $transactionStatus;
        $this->transactionType = $transactionType;
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

    public function getChangedStatus(): string
    {
        return $this->changedStatus;
    }

    public function getNextCharge(): \DateTimeInterface
    {
        return $this->nextCharge;
    }
}