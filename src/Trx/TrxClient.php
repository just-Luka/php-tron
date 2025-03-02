<?php

declare(strict_types=1);

namespace Trx;

use Trx\Data\Account\Account;
use Trx\Domain\Exceptions\ApiRequestException;
use Trx\Domain\Exceptions\InvalidTronAddressException;
use Trx\Domain\Networks\Tron\TronNetwork;
use Trx\Domain\Networks\Tron\Wallet\TronWallet;
use Trx\Domain\TronGrid\AccountTronGrid;

readonly class TrxClient
{
    /**
     * @return TronWallet
     */
    public function wallet(): TronWallet
    {
        return (new TronNetwork())->wallet();
    }

    /**
     * @param $address
     * @return Account
     * @throws ApiRequestException
     * @throws InvalidTronAddressException
     */
    public function explore($address): Account
    {
        return (new AccountTronGrid())->explore($address);
    }
}