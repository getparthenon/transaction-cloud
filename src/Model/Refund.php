<?php

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

    /**
     * @return Money
     */
    public function getTransactionCloudFee(): Money
    {
        return $this->transactionCloudFee;
    }

    /**
     * @return Money
     */
    public function getAmountTotal(): Money
    {
        return $this->amountTotal;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @return string
     */
    public function getHashId(): string
    {
        return $this->hashId;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getInvoiceLink(): string
    {
        return $this->invoiceLink;
    }

    /**
     * @return string
     */
    public function getPaymentProvider(): string
    {
        return $this->paymentProvider;
    }

    /**
     * @return bool
     */
    public function isRefundable(): bool
    {
        return $this->refundable;
    }

    /**
     * @return Money
     */
    public function getTaxAmount(): Money
    {
        return $this->taxAmount;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getTimestamp(): \DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @return Money
     */
    public function getTransactionFee(): Money
    {
        return $this->transactionFee;
    }

    /**
     * @return Money
     */
    public function getVendorIncome(): Money
    {
        return $this->vendorIncome;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }
}