<?php

declare(strict_types=1);

namespace Trx;

use Trx\Domain\Networks\Tron\TronNetwork;
use Trx\Domain\Networks\Tron\Wallet\TronWallet;
use Trx\Domain\TronGrid\V1\AccountTronGrid;

readonly class TrxClient
{
    public function __construct(
        private bool $shastaTest = false,
    ) {}

    /**
     * @return TronWallet
     */
    public function wallet(): TronWallet
    {
        return (new TronNetwork())->wallet();
    }

    /**
     * @return AccountTronGrid
     */
    public function account(): AccountTronGrid
    {
        return new AccountTronGrid();
    }
}