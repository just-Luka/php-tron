<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1;

use Trx\Data\Asset;
use Trx\Domain\Exceptions\ApiRequestException;
use Trx\Domain\TronGrid\V1\Filters\Filterable;

readonly class AssetTronGrid extends BaseTronGrid
{
    use Filterable;

    #[\Override]
    protected function slug(): string
    {
        return '/assets';
    }

    /**
     * @return array<int, Asset>
     * @throws ApiRequestException
     */
    public function all(): array
    {
        $res = json_decode($this->fetch(fn() => $this->endpoint() . "?" . $this->toQuery()), true);

        return array_map(static fn($p) => Asset::fromJson($p), $res['data']);
    }

    /**
     * @param string $name
     * @return array<int, Asset> NOTE: Multiple assets may have the same name.
     * @throws ApiRequestException
     */
    public function byName(string $name): array
    {
        $res = json_decode($this->fetch(fn() => $this->endpoint() . "/$name/list?" . $this->toQuery()), true);

        return array_map(static fn($p) => Asset::fromJson($p), $res['data']);
    }

    /**
     * @param string|int $id The asset ID or owner address (Base58 or Hex format).
     * @return array<int, Asset>
     * @throws ApiRequestException
     */
    public function byId(string|int $id): array
    {
        $res = json_decode($this->fetch(fn() => $this->endpoint() . "/$id?" . $this->toQuery()), true);

        return array_map(static fn($p) => Asset::fromJson($p), $res['data']);
    }
}