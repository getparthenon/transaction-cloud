<?php

namespace TransactionCloud\Model;

use Brick\Money\Currency;
use Brick\Money\Money;
use Brick\Money\MoneyBag;
use TransactionCloud\Exception\MalformedResponseException;
use TransactionCloud\Exception\MissingModelDataException;

class ModelFactory
{
    /**
     * @param array $transaction
     * @return Transaction
     * @throws MissingModelDataException
     */
    public function buildTransaction(array $transaction): Transaction
    {
        $createDate = \DateTime::createFromFormat("Y-m-d", $transaction['createDate']);
        if ($createDate === false) {
            throw new MissingModelDataException("Expected key 'createDate' to contain date format");
        }

        $lastCharge = \DateTime::createFromFormat("Y-m-d", $transaction['lastCharge']);
        if ($lastCharge === false) {
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

}