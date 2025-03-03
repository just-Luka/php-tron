<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1;

use Trx\Data\Account\Account;
use Trx\Data\Block;
use Trx\Data\Transaction\Transaction;
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
     * @return array<int, Block>
     * @throws ApiRequestException
     * @throws InvalidTronAddressException
     */
    public function blocks(string $address): array
    {
        $res = json_decode($this->fetch(
            $address,
            fn() => $this->endpoint() . "/$address/transactions?" . $this->toQuery()
        ), true);

        return array_map(static fn($p) => Block::fromJson($p), $res['data']);
    }

    /**
     * @param string $address
     * @return array<int, Transaction>
     * @throws ApiRequestException
     * @throws InvalidTronAddressException
     */
    public function transactions(string $address): array
    {
        $res = json_decode($this->fetch(
            $address,
            fn() => $this->endpoint() . "/$address/transactions/trc20?" . $this->toQuery()
        ), true);

        return array_map(static fn($p) => Transaction::fromJson($p), $res['data']);
    }
}