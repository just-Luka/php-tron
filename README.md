<br>
<br>
<img src="https://just-luka.github.io/php.svg" width="200" style="vertical-align: middle;" alt="PHP">
<span style="font-size: 44px; font-weight: bold; margin: 0 15px; vertical-align: middle;">X</span>
<img src="https://just-luka.github.io/trongrid.png" width="300" style="vertical-align: middle;" alt="TronGrid">
<br>
<br>
<p>
  <a href="https://github.com/just-Luka/php-tron/releases"><img src="https://poser.pugx.org/just-Luka/php-tron/v/stable" alt="Stable Version"></a>
  <a href="https://www.php.net"><img src="https://img.shields.io/badge/php-%3E=8.2-8993be.svg?maxAge=2592000" alt="Php Version"></a>
  <a href="https://github.com/just-Luka/php-tron/blob/main/LICENSE"><img src="https://img.shields.io/github/license/just-Luka/php-tron.svg?maxAge=2592000" alt="php-tron License"></a>
</p>


# A PHP library for interacting with the TRON blockchain.

...

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

$tron = new TronClient();
```

Initialize the Client (Shasta Testnet)
``` php
use Trx\TrxClient;

$tron = new TronClient(testmode: true);
```

### Example

Generate TRON address
``` php
use Trx\TrxClient;

$tron = new TronClient();
$wallet = $tron->wallet()->create();

echo $wallet->getAddress();
echo $wallet->getPrivateKey();
```