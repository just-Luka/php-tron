<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1;

use Trx\Data\Block;
use Trx\Domain\Exceptions\ApiRequestException;
use Trx\Domain\TronGrid\V1\Filters\Filterable;

readonly class ContractTronGrid extends BaseTronGrid
{
    use Filterable;

    public function __construct(
        private string $contractAddr,
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function slug(): string
    {
        return '/contracts';
    }

    /**
     * @return array<int, Block>
     * @throws ApiRequestException
     */
    public function blocks(): array
    {
        $res = json_decode($this->fetch(
            fn() => $this->endpoint() . "/$this->contractAddr/transactions?" . $this->toQuery()
        ), true);

        return array_map(static fn($p) => Block::fromJson($p), $res['data']);
    }

    /**
     * @return array
     * @throws ApiRequestException
     */
    public function tokens(): array
    {
        return json_decode($this->fetch(
            fn() => $this->endpoint() . "/$this->contractAddr/tokens?" . $this->toQuery()
        ), true)['data'];
    }
}