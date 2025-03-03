<?php

declare(strict_types=1);

namespace Trx\Data\Transaction;

final readonly class TokenInfo
{
    public function __construct(
        public string $symbol,
        public string $address,
        public int $decimals,
        public string $name,
    ) {}
}