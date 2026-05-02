<br>
<br>
<table>
  <tr>
    <td><img src="https://just-luka.github.io/php.svg" width="200" alt="PHP"></td>
    <td><img src="https://just-luka.github.io/x-mark-512.png" width="45" alt="X"></td>
    <td><img src="https://just-luka.github.io/trongrid.png" width="300" alt="TronGrid"></td>
  </tr>
</table>
<br>
<br>
<p>
  <a href="https://github.com/just-Luka/php-tron/releases"><img src="https://poser.pugx.org/just-Luka/php-tron/v/stable" alt="Stable Version"></a>
  <a href="https://www.php.net"><img src="https://img.shields.io/badge/php-%3E=8.2-8993be.svg?maxAge=2592000" alt="Php Version"></a>
  <a href="https://github.com/just-Luka/php-tron/blob/main/LICENSE"><img src="https://img.shields.io/github/license/just-Luka/php-tron.svg?maxAge=2592000" alt="php-tron License"></a>
</p>


# A PHP library for interacting with the TRON blockchain.

Package provides user-friendly extension API. The latest version of TronGrid's proprietary API is v1.
TronGrid API service has the features of low latency, high consistency, high availability and partition fault tolerance.

**Note:** In order to ensure the reasonable allocation of requested resources, all request APIs need to include the  [**API Key**](https://www.trongrid.io/dashboard/keys) parameter.  
> Requests **without an API Key** will be severely limited or may not receive a response.  

### Features
<ul>
    <li>Generate TRON address</li>
    <li>Check account balance</li>
    <li>Check transaction details</li>
    <li>List assets from chain</li>
    <li>List transactions by contract address</li>
</ul>

### Installation
Install the package via Composer:
``` bash
composer require just-Luka/php-tron
```

### Setup

Initialize the client (Mainnet):
``` php
use Trx\TrxClient;

$tron = new TrxClient(apiKey: 'YOUR-API-KEY');
```

Initialize the client (Shasta Testnet):
``` php
use Trx\TrxClient;

$tron = new TrxClient(apiKey: 'YOUR-API-KEY', testMode: true);
```

---

### Wallet

Generate a single TRON address:
``` php
$wallet = $tron->wallet()->create();

echo $wallet->getAddress();    // T...
echo $wallet->getPrivateKey(); // 64-char hex
```

Generate multiple addresses at once:
``` php
$wallets = $tron->wallet()->createMultiple(5);

foreach ($wallets as $wallet) {
    echo $wallet->getAddress();
}
```

---

### Account

Fetch account info and balance:
``` php
$account = $tron->account('TDrbCtdTj5KMo67mtQw2XXu5eTqwwVYoKz')->explore();

echo $account->balance;             // SUN (1 TRX = 1,000,000 SUN)
echo $account->address;
echo $account->createTime;
echo $account->netWindowSize;
echo $account->ownerPermissionThreshold;
echo $account->activePermissionThreshold;

// Energy & bandwidth resources
echo $account->resource->energyWindowSize;
echo $account->resource->energyUsage;
echo $account->resource->energyWindowOptimized;

// TRC20 token balances  [ ['token_contract_address' => balance], ... ]
var_dump($account->trc20);
```

Fetch TRX transfer blocks for an account:
``` php
$blocks = $tron->account('TDrbCtdTj5KMo67mtQw2XXu5eTqwwVYoKz')->blocks();

foreach ($blocks as $block) {
    echo $block->txID;
    echo $block->blockNumber;
    echo $block->blockTimestamp;
    echo $block->contractBalance;
    echo $block->contractOwnerAddress;
    echo $block->contractReceiverAddress;
    echo $block->netFee;
    echo $block->energyFee;
}
```

Fetch TRC20 transactions for an account:
``` php
$transactions = $tron->account('TDrbCtdTj5KMo67mtQw2XXu5eTqwwVYoKz')->transactions();

foreach ($transactions as $tx) {
    echo $tx->transactionId;
    echo $tx->from;
    echo $tx->to;
    echo $tx->value;
    echo $tx->blockTimestamp;
    echo $tx->type;

    // Token info
    echo $tx->tokenInfo->symbol;
    echo $tx->tokenInfo->name;
    echo $tx->tokenInfo->decimals;
    echo $tx->tokenInfo->address;
}
```

---

### Contract

Fetch transactions for a contract address:
``` php
$contract = $tron->contract('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');

$blocks = $contract->blocks();
$tokens = $contract->tokens();
```

---

### Assets

``` php
$asset = $tron->asset();

$all     = $asset->all();               // all assets
$byName  = $asset->byName('BitTorrent'); // by name (may return multiple)
$byId    = $asset->byId(1000001);        // by ID or owner address
```

Each `Asset` object contains:
``` php
$asset->id;
$asset->name;
$asset->abbr;
$asset->description;
$asset->url;
$asset->totalSupply;
$asset->precision;
$asset->ownerAddress;
$asset->startTime;
$asset->endTime;
```

---

### Filters

Filters can be chained on `account()` and `contract()` calls before executing a query.  
Calling the same filter twice overwrites the previous value.

| Method | Parameter | Description |
|--------|-----------|-------------|
| `filterOnlyConfirmed()` | — | Return only confirmed transactions. Cannot be combined with `filterOnlyUnconfirmed()`. |
| `filterOnlyUnconfirmed()` | — | Return only unconfirmed transactions. Cannot be combined with `filterOnlyConfirmed()`. |
| `filterLimit(int)` | `1–200` (default `20`) | Number of results per page. |
| `filterFingerprint(string)` | fingerprint from previous response | Pagination cursor. Other filters must remain the same across pages. |
| `filterOrderBy(string)` | `block_timestamp,asc` \| `block_timestamp,desc` | Sort order (default `desc`). |
| `filterMinTimestamp(string)` | Unix ms timestamp | Return results after this timestamp. |
| `filterMaxTimestamp(string)` | Unix ms timestamp | Return results before this timestamp. |
| `filterContractAddress(string)` | Base58 or hex address | Filter by a specific token contract. |
| `filterOnlyTo()` | — | Only transactions **to** this address. |
| `filterOnlyFrom()` | — | Only transactions **from** this address. |

Example — paginate confirmed USDT transfers in a time window:
``` php
$account = $tron->account('TDrbCtdTj5KMo67mtQw2XXu5eTqwwVYoKz');

$transactions = $account
    ->filterOnlyConfirmed()
    ->filterLimit(50)
    ->filterContractAddress('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t') // USDT
    ->filterMinTimestamp('1700000000000')
    ->filterMaxTimestamp('1710000000000')
    ->filterOrderBy('block_timestamp,asc')
    ->transactions();
```

Fetch the next page using the fingerprint from the last result:
``` php
$lastTx      = end($transactions);
$nextPage    = $account
    ->filterOnlyConfirmed()
    ->filterLimit(50)
    ->filterFingerprint($lastTx->transactionId)
    ->transactions();
```

---

### Error Handling

``` php
use Trx\Domain\Exceptions\ApiRequestException;
use Trx\Domain\Exceptions\InvalidTronAddressException;

try {
    $account = $tron->account('invalid-address');
} catch (InvalidTronAddressException $e) {
    // Thrown immediately when the address format is invalid (no API call made)
}

try {
    $result = $tron->account('TDrbCtdTj5KMo67mtQw2XXu5eTqwwVYoKz')->explore();
    // Returns null if the account does not exist on-chain
} catch (ApiRequestException $e) {
    // Thrown on non-200 API responses or network failures
    echo $e->getMessage();
}
```
