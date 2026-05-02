<?php

declare(strict_types=1);

namespace Trx\Domain\Networks\Tron;

use Elliptic\EC;
use Trx\Domain\Contracts\Generatable;
use Trx\Domain\Networks\Tron\Wallet\TronWallet;

final class TronNetwork implements Generatable
{
    private readonly string $privateKeyHex;

    public function __construct()
    {
        $this->privateKeyHex = bin2hex(random_bytes(32));
    }

    /**
     * @return string
     */
    public function publicKey(): string
    {
        $keyPair = (new EC('secp256k1'))->keyFromPrivate($this->privateKeyHex);

        return substr($keyPair->getPublic(false, 'hex'), 2);
    }

    /**
     * @return string
     */
    public function privateKey(): string
    {
        return $this->privateKeyHex;
    }

    /**
     * @return TronWallet
     */
    public function wallet(): TronWallet
    {
        return new TronWallet($this);
    }
}