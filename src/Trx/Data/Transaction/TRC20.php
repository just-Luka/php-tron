<?php

declare(strict_types=1);

namespace Trx\Data\Transaction;

final readonly class TRC20
{
    public function __construct(
        public string $transactionId,
        public TokenInfo $tokenInfo,
        public int $blockTimestamp,
        public string $from,
        public string $to,
        public string $type,
        public string $value
    ) {}
}