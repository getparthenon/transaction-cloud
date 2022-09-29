<?php

declare(strict_types=1);

/*
 *
 *     This file is part of the Transaction.Cloud PHP SDK.
 *     Copyright Humbly Arrogant Ltd 2022
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 */

namespace TransactionCloud\Model;

use Brick\Money\Currency;
use Brick\Money\Money;

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
    private Money $netPrice;
    private Money $tax;
    private Currency $currency;

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
        Money $netPrice,
        Money $tax,
        Currency $currency
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

    public function getNetPrice(): Money
    {
        return $this->netPrice;
    }

    public function getTax(): Money
    {
        return $this->tax;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
