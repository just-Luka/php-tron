<?php

declare(strict_types=1);

namespace Trx;

use Trx\Domain\Exceptions\InvalidTronAddressException;
use Trx\Domain\Facades\Validator;
use Trx\Domain\Networks\Tron\TronNetwork;
use Trx\Domain\Networks\Tron\Wallet\TronWallet;
use Trx\Domain\TronGrid\V1\AccountTronGrid;
use Trx\Domain\TronGrid\V1\AssetTronGrid;
use Trx\Domain\TronGrid\V1\ContractTronGrid;

readonly class TrxClient
{
    public function __construct(
        public ?string $apiKey = null,
        public bool $testMode = false,
    ) {}

    /**
     * @return TronWallet
     */
    public function wallet(): TronWallet
    {
        return (new TronNetwork())->wallet();
    }

    /**
     * @param string $address owner address in base58
     * @return AccountTronGrid
     * @throws InvalidTronAddressException
     */
    public function account(string $address): AccountTronGrid
    {
        if(! Validator::isTrxBase58($address)) {
            throw new InvalidTronAddressException('Trx address is not valid');
        };

        return new AccountTronGrid($address);
    }

    /**
     * @return AssetTronGrid
     */
    public function asset(): AssetTronGrid
    {
        return new AssetTronGrid();
    }

    /**
     * @param string $address The address of contract in base58
     * @return ContractTronGrid
     * @throws InvalidTronAddressException
     */
    public function contract(string $address): ContractTronGrid
    {
        if(! Validator::isTrxBase58($address)) {
            throw new InvalidTronAddressException('Trx address is not valid');
        };

        return new ContractTronGrid($address);
    }
}