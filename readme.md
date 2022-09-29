Transaction.Cloud PHP SDK 
=================

This is a library to provide integration to the [Transaction.cloud](https://hosted.transaction.cloud/ref/6HBUF3G5) payment provider system.

[Transaction.cloud](https://hosted.transaction.cloud/ref/6HBUF3G5) is a reseller payment provider. Where it acts as the merchant of record instead of yourself. This means they handle all the legal paperwork required for the actual sale and you just get the money without all the headaches.

## Getting Started

```sh
composer require parthenon/transaction-cloud
```

```php
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");

// and if you want to use the sandbox

$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password", true);
```

### Get Url To Manage Transactions

[API Docs](https://app.transaction.cloud/api-docs/#retrieve-url-to-manage-transactions)

```php 
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$url = $transactionCloud->getUrlToManageTransactions("iain.cambridge@example.org");
```

### Get URL to Admin Dashboard

[API Docs](https://app.transaction.cloud/api-docs/#retrieve-url-of-hosted-admin-app)

```php 
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$url = $transactionCloud->getUrlToAdmin();
```

## Retrieve Transactions By Email

[API Docs](https://app.transaction.cloud/api-docs/#retrieve-transactions-by-email)

```php
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$transactions = $transactionCloud->getTransactionsByEmail("iain.cambridge@example.org");

/** $transaction \TransactionCloud\Model\Transaction **/
foreach ($transactions as $transaction) {
    // Do something with transaction
}
```