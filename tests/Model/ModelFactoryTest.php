<?php

namespace Tests\TransactionCloud\Model;

use PHPUnit\Framework\TestCase;
use TransactionCloud\Exception\MissingModelDataException;
use TransactionCloud\Model\ModelFactory;
use TransactionCloud\Model\Refund;
use TransactionCloud\Model\Transaction;

class ModelFactoryTest extends TestCase
{
    public function testInvalidCreateDateEmptyString()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'createDate' to contain date format");

        $transactionData = [
            'createDate' => ''
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidLastChargeEmptyString()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'lastCharge' to contain date format");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => ''
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidAssignedEmail()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'assignedEmail' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => null
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidChargeFrequency()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'chargeFrequency' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => null,
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidCountry()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'country' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => null
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidEmail()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'email' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => "US",
            'email' => null,
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidId()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'id' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => null
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidPayload()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'payload' to contain a string or null");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => 'TC-PR_zzzyyxx',
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidProductId()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'productId' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => 'TC-PR_zzzyyxx',
            'payload' => null,
            'productId' => null
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidProductName()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'productName' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => 'TC-TR_zzzyyxx',
            'payload' => null,
            'productId' => 'TC-PR_dskfjsdl',
            'productName' => null,
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidTransactionStatus()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'transactionStatus' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => 'TC-PR_zzzyyxx',
            'payload' => null,
            'productId' => 'TC-PR_dskfjsdl',
            'productName' => 'Product Name',
            'transactionStatus' => null,
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidTransactionType()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'transactionType' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => 'TC-PR_zzzyyxx',
            'payload' => null,
            'productId' => 'TC-PR_dskfjsdl',
            'productName' => 'Product Name',
            'transactionStatus' => 'SUBSCRIPTION_STATUS_ACTIVE',
            'transactionType' => null
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidNetPrice()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'netPrice' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => 'TC-PR_zzzyyxx',
            'payload' => null,
            'productId' => 'TC-PR_dskfjsdl',
            'productName' => 'Product Name',
            'transactionStatus' => 'SUBSCRIPTION_STATUS_ACTIVE',
            'transactionType' => 'SUBSCRIPTION',
            'netPrice' => null,
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidTax()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'tax' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => 'TC-PR_zzzyyxx',
            'payload' => null,
            'productId' => 'TC-PR_dskfjsdl',
            'productName' => 'Product Name',
            'transactionStatus' => 'SUBSCRIPTION_STATUS_ACTIVE',
            'transactionType' => 'SUBSCRIPTION',
            'netPrice' => "10.3",
            'tax' => null,
            'currency' => null
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testInvalidCurrency()
    {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'currency' to contain a string");

        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => 'TC-PR_zzzyyxx',
            'payload' => null,
            'productId' => 'TC-PR_dskfjsdl',
            'productName' => 'Product Name',
            'transactionStatus' => 'SUBSCRIPTION_STATUS_ACTIVE',
            'transactionType' => 'SUBSCRIPTION',
            'netPrice' => "10.3",
            'tax' => "1.0",
            'currency' => null
        ];

        $subject = new ModelFactory();
        $subject->buildTransaction($transactionData);
    }

    public function testValid()
    {
        $transactionData = [
            'createDate' => '2000-03-23',
            'lastCharge' => '2001-05-23',
            'assignedEmail' => '',
            'chargeFrequency' => 'WEEKLY',
            'country' => 'US',
            'email' => 'iain.cambridge@example.org',
            'id' => 'TC-PR_zzzyyxx',
            'payload' => null,
            'productId' => 'TC-PR_dskfjsdl',
            'productName' => 'Product Name',
            'transactionStatus' => 'SUBSCRIPTION_STATUS_ACTIVE',
            'transactionType' => 'SUBSCRIPTION',
            'netPrice' => "10.3",
            'tax' => "1.0",
            'currency' => "USD"
        ];

        $subject = new ModelFactory();
        $actual = $subject->buildTransaction($transactionData);

        $this->assertInstanceOf(Transaction::class, $actual);
    }

    public function testRefundInvalidTcFee() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'TCFee' to contain a value");

        $refundData = [
            'TCFee' => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_amountTotal() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'amountTotal' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => null,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_currency() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'currency' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => null,
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }


    public function testRefundInvalid_externalId() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'externalId' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => null,
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_hashId() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'hashId' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => null,
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_id() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'id' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => null,
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_incomeCurrency() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'incomeCurrency' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => null,
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_invoiceLink() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'invoiceLink' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => null,
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_paymentProvider() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'paymentProvider' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => null,
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_refundable() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'refundable' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => null,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_taxAmount() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'taxAmount' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => null,
            "timestamp" => 1643974626000,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_timestamp() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'timestamp' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => null,
            "transactionsFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_transactionFee() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'transactionFee' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionFee" => null,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalid_vendorIncome() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'vendorIncome' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionFee" => 0.0,
            "vendorIncome" => null,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundInvalidcountry() {
        $this->expectException(MissingModelDataException::class);
        $this->expectExceptionMessage("Expected key 'country' to contain a value");

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => null,
        ];

        $subject = new ModelFactory();
        $subject->buildRefund($refundData);
    }

    public function testRefundValid() {

        $refundData = [
            'TCFee' => 0.72,
            'amountTotal' => 4.36,
            'currency' => 'USD',
            'externalId' => "5559995555",
            "hashId" => "TC-BA_YYYZZZX",
            "id" => "4/2/2022",
            "incomeCurrency" => "USD",
            "invoiceLink" => "https://api.transaction.cloud/invoice/TC-BA_YYYZZZX",
            "paymentProvider" => "BLUESNAP",
            "refundable" => false,
            "taxAmount" => 0.36,
            "timestamp" => 1643974626000,
            "transactionFee" => 0.0,
            "vendorIncome" => 3.28,
            "country" => "US",
        ];

        $subject = new ModelFactory();
        $actual = $subject->buildRefund($refundData);

        $this->assertInstanceOf(Refund::class, $actual);
    }
}