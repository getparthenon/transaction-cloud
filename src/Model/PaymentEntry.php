<?php

namespace TransactionCloud\Model;

use Brick\Money\Currency;
use Brick\Money\Money;

class PaymentEntry {

    private Money $affilateIncome;

    private Currency $affilateIncomeCurrency;

    private Money $amountTotal;

    private string $country;

    private \DateTimeInterface $createDate;

    private Currency $currency;

    private string $id;

    private Money $income;

    private Currency $incomeCurrency;

    private Money $taxAmount;

    private float $taxRate;

    private string $type;

    /**
     * PaymentEntry constructor.
     * @param Money $affilateIncome
     * @param Currency $affilateIncomeCurrency
     * @param Money $amountTotal
     * @param string $country
     * @param \DateTimeInterface $createDate
     * @param Currency $currency
     * @param string $id
     * @param Money $income
     * @param Currency $incomeCurrency
     * @param Money $taxAmount
     * @param float $taxRate
     * @param string $type
     */
    public function __construct(Money $affilateIncome, Currency $affilateIncomeCurrency, Money $amountTotal, string $country, \DateTimeInterface $createDate, Currency $currency, string $id, Money $income, Currency $incomeCurrency, Money $taxAmount, float $taxRate, string $type)
    {
        $this->affilateIncome = $affilateIncome;
        $this->affilateIncomeCurrency = $affilateIncomeCurrency;
        $this->amountTotal = $amountTotal;
        $this->country = $country;
        $this->createDate = $createDate;
        $this->currency = $currency;
        $this->id = $id;
        $this->income = $income;
        $this->incomeCurrency = $incomeCurrency;
        $this->taxAmount = $taxAmount;
        $this->taxRate = $taxRate;
        $this->type = $type;
    }

    /**
     * @return Money
     */
    public function getAffilateIncome(): Money
    {
        return $this->affilateIncome;
    }

    /**
     * @return Currency
     */
    public function getAffilateIncomeCurrency(): Currency
    {
        return $this->affilateIncomeCurrency;
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
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreateDate(): \DateTimeInterface
    {
        return $this->createDate;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Money
     */
    public function getIncome(): Money
    {
        return $this->income;
    }

    /**
     * @return Currency
     */
    public function getIncomeCurrency(): Currency
    {
        return $this->incomeCurrency;
    }

    /**
     * @return Money
     */
    public function getTaxAmount(): Money
    {
        return $this->taxAmount;
    }

    /**
     * @return float
     */
    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}