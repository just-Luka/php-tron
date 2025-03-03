<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1;

use Trx\Data\Account\Account;
use Trx\Domain\Enum\ContractType;
use Trx\Domain\Exceptions\ApiRequestException;
use Trx\Domain\Exceptions\InvalidTronAddressException;
use Trx\Domain\TronGrid\V1\Filters\Filterable;

readonly class AccountTronGrid extends BaseTronGrid
{
    use Filterable;

    protected function slug(): string
    {
        return '/accounts';
    }

    /**
     * @param string $address
     * @return Account
     * @throws ApiRequestException
     * @throws InvalidTronAddressException
     */
    public function explore(string $address): Account
    {
        return Account::fromJson($this->fetch(
            $address,
            fn() => $this->endpoint() . "/$address?" . $this->toQuery()
        ));
    }

    /**
     * @param string $address
     * @param ContractType|null $contract
     * @return string
     * @throws ApiRequestException
     * @throws InvalidTronAddressException
     */
    public function transactions(string $address, ?ContractType $contract = null): string
    {
        $contractPath = $contract?->value ? $contract->value : '';
        return $this->fetch(
            $address,
            fn() => $this->endpoint() . "/$address/transactions/$contractPath?" . $this->toQuery()
        );
    }
}