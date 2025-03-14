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
``` php
composer require just-Luka/php-tron
```

### Usage

Initialize the Client (Mainnet)
``` php
use Trx\TrxClient;

$tron = new TronClient('API-KEY');
```

Initialize the Client (Shasta Testnet)
``` php
use Trx\TrxClient;

$tron = new TronClient(testmode: true);
```

### Example

Generate TRON address
``` php
$wallet = $tron->wallet()->create();

echo $wallet->getAddress();
echo $wallet->getPrivateKey();
```

TRON balance
``` php
$account = $tron->account('TDrbCtdTj5KMo67mtQw2XXu5eTqwwVYoKz');

echo $account->explore()->balance;
```

Transactions
``` php
$account = $tron->account('TDrbCtdTj5KMo67mtQw2XXu5eTqwwVYoKz');

$transactions = $account->transactions();
# Or
$transactions = $account->filterLimit(10)->filterOnlyConfirmed()->transactions(); # Apply filters

var_dump($transactions);
```

