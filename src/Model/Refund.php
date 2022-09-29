<?php

declare(strict_types=1);

/*
 * This file is part of the Transaction.Cloud PHP SDK.
 * Copyright Humbly Arrogant Ltd 2022
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace TransactionCloud\Model;

use Brick\Money\Currency;
use Brick\Money\Money;

class Refund
{
    private Money $transactionCloudFee;

    private Money $amountTotal;

    private Currency $currency;

    private string $externalId;

    private string $hashId;

    private string $id;

    private Currency $incomeCurrency;

    private string $invoiceLink;

    private string $paymentProvider;

    private bool $refundable;

    private Money $taxAmount;

    private \DateTimeInterface $timestamp;

    private Money $transactionFee;

    private Money $vendorIncome;

    private string $country;

    public function __construct(
        Money $transactionCloudFee,
        Money $amountTotal,
        Currency $currency,
        string $externalId,
        string $hashId,
        string $id,
        Currency $incomeCurrency,
        string $invoiceLink,
        string $paymentProvider,
        bool $refundable,
        Money $taxAmount,
        \DateTimeInterface $timestamp,
        Money $transactionFee,
        Money $vendorIncome,
        string $country
    ) {
        $this->transactionCloudFee = $transactionCloudFee;
        $this->amountTotal = $amountTotal;
        $this->currency = $currency;
        $this->externalId = $externalId;
        $this->hashId = $hashId;
        $this->id = $id;
        $this->incomeCurrency = $incomeCurrency;
        $this->invoiceLink = $invoiceLink;
        $this->paymentProvider = $paymentProvider;
        $this->refundable = $refundable;
        $this->taxAmount = $taxAmount;
        $this->timestamp = $timestamp;
        $this->transactionFee = $transactionFee;
        $this->vendorIncome = $vendorIncome;
        $this->country = $country;
    }

    public function getTransactionCloudFee(): Money
    {
        return $this->transactionCloudFee;
    }

    public function getAmountTotal(): Money
    {
        return $this->amountTotal;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getHashId(): string
    {
        return $this->hashId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIncomeCurrency(): Currency
    {
        return $this->incomeCurrency;
    }

    public function getInvoiceLink(): string
    {
        return $this->invoiceLink;
    }

    public function getPaymentProvider(): string
    {
        return $this->paymentProvider;
    }

    public function isRefundable(): bool
    {
        return $this->refundable;
    }

    public function getTaxAmount(): Money
    {
        return $this->taxAmount;
    }

    public function getTimestamp(): \DateTimeInterface
    {
        return $this->timestamp;
    }

    public function getTransactionFee(): Money
    {
        return $this->transactionFee;
    }

    public function getVendorIncome(): Money
    {
        return $this->vendorIncome;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}
