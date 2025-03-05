<?php

declare(strict_types=1);

namespace Trx\Domain\Clients;

use Trx\Domain\Exceptions\InvalidTronAddressException;
use Trx\Domain\Facades\Validator;
use Trx\Domain\TronGrid\V1\AccountTronGrid;
use Trx\Domain\TronGrid\V1\AssetTronGrid;
use Trx\Domain\TronGrid\V1\ContractTronGrid;

trait TronGridV1
{
    /**
     * @param string $address Owner address in base58
     * @return AccountTronGrid
     * @throws InvalidTronAddressException
     */
    public function account(string $address): AccountTronGrid
    {
        $this->validateAddress($address);
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
        $this->validateAddress($address);
        return new ContractTronGrid($address);
    }

    /**
     * Validates if the given address is a valid Tron address.
     *
     * @throws InvalidTronAddressException
     */
    private function validateAddress(string $address): void
    {
        if (!Validator::isTrxBase58($address)) {
            throw new InvalidTronAddressException('Trx address is not valid');
        }
    }
}
