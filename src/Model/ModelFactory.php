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

use Brick\Math\BigNumber;
use Brick\Money\Currency;
use Brick\Money\Money;
use TransactionCloud\Exception\MissingModelDataException;

class ModelFactory
{
    /**
     * @throws MissingModelDataException
     */
    public function buildTransaction(array $transaction): Transaction
    {
        $createDate = \DateTime::createFromFormat('Y-m-d', $transaction['createDate']);
        if (false === $createDate) {
            throw new MissingModelDataException("Expected key 'createDate' to contain date format");
        }

        $lastCharge = \DateTime::createFromFormat('Y-m-d', $transaction['lastCharge']);
        if (false === $lastCharge) {
            throw new MissingModelDataException("Expected key 'lastCharge' to contain date format");
        }

        if (!isset($transaction['assignedEmail'])) {
            throw new MissingModelDataException("Expected key 'assignedEmail' to contain a string");
        }
        if (!isset($transaction['chargeFrequency'])) {
            throw new MissingModelDataException("Expected key 'chargeFrequency' to contain a string");
        }

        if (!isset($transaction['country'])) {
            throw new MissingModelDataException("Expected key 'country' to contain a string");
        }

        if (!isset($transaction['email'])) {
            throw new MissingModelDataException("Expected key 'email' to contain a string");
        }

        if (!isset($transaction['id'])) {
            throw new MissingModelDataException("Expected key 'id' to contain a string");
        }

        if (!array_key_exists('payload', $transaction)) {
            throw new MissingModelDataException("Expected key 'payload' to contain a string or null");
        }
        if (!isset($transaction['productId'])) {
            throw new MissingModelDataException("Expected key 'productId' to contain a string");
        }
        if (!isset($transaction['productName'])) {
            throw new MissingModelDataException("Expected key 'productName' to contain a string");
        }
        if (!isset($transaction['transactionStatus'])) {
            throw new MissingModelDataException("Expected key 'transactionStatus' to contain a string");
        }
        if (!isset($transaction['transactionType'])) {
            throw new MissingModelDataException("Expected key 'transactionType' to contain a string");
        }
        if (!isset($transaction['netPrice'])) {
            throw new MissingModelDataException("Expected key 'netPrice' to contain a string");
        }
        if (!isset($transaction['tax'])) {
            throw new MissingModelDataException("Expected key 'tax' to contain a string");
        }
        if (!isset($transaction['currency'])) {
            throw new MissingModelDataException("Expected key 'currency' to contain a string");
        }
        $currency = Currency::of($transaction['currency']);

        $netPrice = Money::of($transaction['netPrice'], $currency);
        $tax = Money::of($transaction['tax'], $currency);

        return new Transaction(
            $transaction['assignedEmail'],
            $transaction['chargeFrequency'],
            $transaction['country'],
            $createDate,
            $transaction['email'],
            $transaction['id'],
            $lastCharge,
            $transaction['payload'],
            $transaction['productId'],
            $transaction['productName'],
            $transaction['transactionStatus'],
            $transaction['transactionType'],
            $netPrice,
            $tax,
            $currency,
        );
    }

    public function buildRefund(array $refundData): Refund
    {
        if (!isset($refundData['TCFee'])) {
            throw new MissingModelDataException("Expected key 'TCFee' to contain a value");
        }
        if (!isset($refundData['amountTotal'])) {
            throw new MissingModelDataException("Expected key 'amountTotal' to contain a value");
        }
        if (!isset($refundData['currency'])) {
            throw new MissingModelDataException("Expected key 'currency' to contain a value");
        }
        if (!isset($refundData['externalId'])) {
            throw new MissingModelDataException("Expected key 'externalId' to contain a value");
        }
        if (!isset($refundData['hashId'])) {
            throw new MissingModelDataException("Expected key 'hashId' to contain a value");
        }
        if (!isset($refundData['id'])) {
            throw new MissingModelDataException("Expected key 'id' to contain a value");
        }
        if (!isset($refundData['incomeCurrency'])) {
            throw new MissingModelDataException("Expected key 'incomeCurrency' to contain a value");
        }
        if (!isset($refundData['invoiceLink'])) {
            throw new MissingModelDataException("Expected key 'invoiceLink' to contain a value");
        }
        if (!isset($refundData['paymentProvider'])) {
            throw new MissingModelDataException("Expected key 'paymentProvider' to contain a value");
        }
        if (!isset($refundData['refundable'])) {
            throw new MissingModelDataException("Expected key 'refundable' to contain a value");
        }
        if (!isset($refundData['taxAmount'])) {
            throw new MissingModelDataException("Expected key 'taxAmount' to contain a value");
        }
        if (!isset($refundData['timestamp'])) {
            throw new MissingModelDataException("Expected key 'timestamp' to contain a value");
        }
        if (!isset($refundData['transactionFee'])) {
            throw new MissingModelDataException("Expected key 'transactionFee' to contain a value");
        }
        if (!isset($refundData['vendorIncome'])) {
            throw new MissingModelDataException("Expected key 'vendorIncome' to contain a value");
        }
        if (!isset($refundData['country'])) {
            throw new MissingModelDataException("Expected key 'country' to contain a value");
        }

        $currency = Currency::of($refundData['currency']);
        $incomeCurrency = Currency::of($refundData['incomeCurrency']);

        $tcFeeNumber = BigNumber::of($refundData['TCFee']);
        $tcFee = Money::of($tcFeeNumber, $currency);

        $amountTotalNumber = BigNumber::of($refundData['amountTotal']);
        $amountTotalFee = Money::of($amountTotalNumber, $currency);

        $taxAmountNumber = BigNumber::of($refundData['taxAmount']);
        $taxAmountFee = Money::of($taxAmountNumber, $currency);

        $transactionFeeNumber = BigNumber::of($refundData['transactionFee']);
        $transactionFee = Money::of($transactionFeeNumber, $currency);

        $vendorIncomeNumber = BigNumber::of($refundData['vendorIncome']);
        $vendorIncome = Money::of($vendorIncomeNumber, $incomeCurrency);

        $timestamp = new \DateTime();
        $timestamp->setTimestamp($refundData['timestamp']);

        return new Refund(
            $tcFee,
            $amountTotalFee,
            $currency,
            $refundData['externalId'],
            $refundData['hashId'],
            $refundData['id'],
            $incomeCurrency,
            $refundData['invoiceLink'],
            $refundData['paymentProvider'],
            $refundData['refundable'],
            $taxAmountFee,
            $timestamp,
            $transactionFee,
            $vendorIncome,
            $refundData['country']
        );
    }

    /**
     * @return Transaction
     *
     * @throws MissingModelDataException
     */
    public function buildChangedTransaction(array $transaction): ChangedTransaction
    {
        $createDate = \DateTime::createFromFormat('Y-m-d', $transaction['createDate']);
        if (false === $createDate) {
            throw new MissingModelDataException("Expected key 'createDate' to contain date format");
        }

        $lastCharge = \DateTime::createFromFormat('Y-m-d', $transaction['lastCharge']);
        if (false === $lastCharge) {
            throw new MissingModelDataException("Expected key 'lastCharge' to contain date format");
        }

        $nextCharge = \DateTime::createFromFormat('Y-m-d', $transaction['nextCharge']);
        if (false === $nextCharge) {
            throw new MissingModelDataException("Expected key 'nextCharge' to contain date format");
        }

        if (!isset($transaction['assignedEmail'])) {
            throw new MissingModelDataException("Expected key 'assignedEmail' to contain a string");
        }

        if (!isset($transaction['changedStatus'])) {
            throw new MissingModelDataException("Expected key 'changedStatus' to contain a string");
        }

        if (!isset($transaction['chargeFrequency'])) {
            throw new MissingModelDataException("Expected key 'chargeFrequency' to contain a string");
        }

        if (!isset($transaction['country'])) {
            throw new MissingModelDataException("Expected key 'country' to contain a string");
        }

        if (!isset($transaction['email'])) {
            throw new MissingModelDataException("Expected key 'email' to contain a string");
        }

        if (!isset($transaction['id'])) {
            throw new MissingModelDataException("Expected key 'id' to contain a string");
        }

        if (!array_key_exists('payload', $transaction)) {
            throw new MissingModelDataException("Expected key 'payload' to contain a string or null");
        }
        if (!isset($transaction['productId'])) {
            throw new MissingModelDataException("Expected key 'productId' to contain a string");
        }
        if (!isset($transaction['productName'])) {
            throw new MissingModelDataException("Expected key 'productName' to contain a string");
        }
        if (!isset($transaction['transactionStatus'])) {
            throw new MissingModelDataException("Expected key 'transactionStatus' to contain a string");
        }
        if (!isset($transaction['transactionType'])) {
            throw new MissingModelDataException("Expected key 'transactionType' to contain a string");
        }

        return new ChangedTransaction(
            $transaction['assignedEmail'],
            $transaction['changedStatus'],
            $transaction['chargeFrequency'],
            $transaction['country'],
            $createDate,
            $transaction['email'],
            $transaction['id'],
            $lastCharge,
            $nextCharge,
            $transaction['payload'],
            $transaction['productId'],
            $transaction['productName'],
            $transaction['transactionStatus'],
            $transaction['transactionType'],
        );
    }

    public function buildProductData(array $productData): ProductData
    {
        if (!isset($productData['link'])) {
            throw new MissingModelDataException("Expected key 'link' to contain a string");
        }

        if (!isset($productData['customProductId'])) {
            throw new MissingModelDataException("Expected key 'customProductId' to contain a string");
        }

        return new ProductData(
            $productData['link'],
            $productData['customProductId'],
        );
    }
}
