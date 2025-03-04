<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1;

use Trx\Data\Account\Account;
use Trx\Data\Block;
use Trx\Data\Transaction\Transaction;
use Trx\Domain\Exceptions\ApiRequestException;
use Trx\Domain\TronGrid\V1\Filters\Filterable;

readonly class AccountTronGrid extends BaseTronGrid
{
    use Filterable;

    public function __construct(
        private string $address
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function slug(): string
    {
        return '/accounts';
    }

    /**
     * @return Account
     * @throws ApiRequestException
     */
    public function explore(): Account
    {
        return Account::fromJson($this->fetch(
            fn() => $this->endpoint() . "/$this->address?" . $this->toQuery()
        ));
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