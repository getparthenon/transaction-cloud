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

## Examples

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

### Retrieve Transactions By Email

[API Docs](https://app.transaction.cloud/api-docs/#retrieve-transactions-by-email)

```php
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$transactions = $transactionCloud->getTransactionsByEmail("iain.cambridge@example.org");

/** @var $transaction \TransactionCloud\Model\Transaction **/
foreach ($transactions as $transaction) {
    // Do something with transaction
}
```

### Retrieve Transaction By Transaction ID

[API Docs](https://app.transaction.cloud/api-docs/#retrieve-transaction-by-transaction-id)

```php
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$transaction = $transactionCloud->getTransactionById("TC-TR_xxyyxxx");

// do something with transaction.
```

### Assign Email Address By Transaction Id

[API Docs](https://app.transaction.cloud/api-docs/#assign-email-address-by-transaction-id)

```php
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$success = $transactionCloud->assignTransactionToEmail("TC-TR_xxyyxxx", "new.iain@example.org");
```

### Cancel Subscription

[API Docs](https://app.transaction.cloud/api-docs/#cancel-subscription)

```php
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$success = $transactionCloud->cancelSubscription("TC-TR_xxyyxxx");
```

### Refund Transaction


[API Docs](https://app.transaction.cloud/api-docs/#refund-transaction)

```php
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$refundData = $transactionCloud->refundTransaction("TC-TR_xxyyxxx");
```

### Retrieve Transactions With Changed Status

[API Docs](https://app.transaction.cloud/api-docs/#retrieve-transactions-with-changed-status)

```php
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$transactions = $transactionCloud->fetchChangedTransactions();

/** @var $transaction \TransactionCloud\Model\ChangedTransaction **/
foreach ($transactions as $transaction) {
    // Do something with transaction
}
```

### Marking Transaction As Processed

[API Docs](https://app.transaction.cloud/api-docs/#marking-transactions-as-processed)

```php
$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$success = $transactionCloud->markTransactionAsProcessed("TC-TR_xxyyxxx");
```

### Customise Product On Demand

[API Docs](https://app.transaction.cloud/api-docs/#customize-product-on-demand)

```php
$price = \Brick\Money::of("100", "USD");

$product = new \TransactionCloud\Model\Product();
$product->setPrices([$price]);
$product->setDescription("Custom product");
$product->setPayload("Payload");
$product->settTansactionIdToMigrate("TC-PR_kdljfdskl");

$transactionCloud = new \TransactionCloud\TransactionCloud::create("api_key", "api_key_password");
$productData = $transacloudCloud->customizeProduct("TC-PR_kdjfde", $product);

// $productData instanceof \TransactionCloud\Model\ProductData
```

## FAQ

### Is this free to use?

Yes. This library is completely open source and is free to use without limitations.

### What support can I get for this?

You can create an issue in this repository and I'll be able to provide support.

### Is this an offical SDK?

No, however, we do have a relationship with Transaction.Cloud.

### Why should I use Transaction.Cloud over Paddle?

It's cheaper. 4.9% vs 5%. Not much but it all adds up. 

They're also very responsive in their support matters. We get answers within hours.

[Click here to check them out](https://hosted.transaction.cloud/ref/6HBUF3G5)