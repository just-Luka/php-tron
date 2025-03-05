<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1;

use Trx\Data\Account\Account;
use Trx\Data\Block;
use Trx\Data\Transaction\Transaction;
use Trx\Domain\Exceptions\ApiRequestException;
use Trx\Domain\TronGrid\V1\Filters\Filterable;

class AccountTronGrid extends BaseTronGrid
{
    use Filterable;

    /**
     * @param string $address
     */
    public function __construct(
        private readonly string $address
    ) {}

    #[\Override]
    protected function slug(): string
    {
        return '/accounts';
    }

    /**
     * @return ?Account
     * @throws ApiRequestException
     */
    public function explore(): ?Account
    {
        $res = json_decode($this->fetch(
            fn() => $this->endpoint() . "/$this->address?" . $this->toQuery()
        ), true);

        return isset($res['data'][0]) ? Account::fromJson($res['data'][0]) : null;
    }

    /**
     * @return array<int, Block>
     * @throws ApiRequestException
     */
    public function blocks(): array
    {
        $res = json_decode($this->fetch(
            fn() => $this->endpoint() . "/$this->address/transactions?" . $this->toQuery()
        ), true);

        return array_map(static fn($p) => Block::fromJson($p), $res['data']);
    }

    /**
     * @return array<int, Transaction>
     * @throws ApiRequestException
     */
    public function transactions(): array
    {
        $res = json_decode($this->fetch(
            fn() => $this->endpoint() . "/$this->address/transactions/trc20?" . $this->toQuery()
        ), true);

        return array_map(static fn($p) => Transaction::fromJson($p), $res['data']);
    }
}