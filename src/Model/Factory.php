<?php

namespace TransactionCloud\Model;

use TransactionCloud\Exception\MissingModelDataException;

class Factory
{
    /**
     * @param array $transaction
     * @return Transaction
     * @throws MissingModelDataException
     */
    public function buildTransaction(array $transaction): Transaction
    {
        $createDate = \DateTime::createFromFormat("Y-m-d", $transaction['createDate']);
        $lastCharge = \DateTime::createFromFormat("Y-m-d", $transaction['lastCharge']);

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
            $transaction['netPrice'],
            $transaction['tax'],
            $transaction['currency'],
        );
    }
}