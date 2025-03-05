<?php

declare(strict_types=1);

namespace Trx\Domain\Clients;

use Trx\Domain\Networks\Tron\TronNetwork;
use Trx\Domain\Networks\Tron\Wallet\TronWallet;

trait TronNet
{
    /**
     * @return TronWallet
     */
    public function wallet(): TronWallet
    {
        return (new TronNetwork())->wallet();
    }
}