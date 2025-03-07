<?php

declare(strict_types=1);

namespace Trx\Data\Account;

final readonly class AccountResource
{
    public function __construct(
        public bool $energyWindowOptimized,
        public ?int $energyUsage,
        public ?int $latestConsumeTimeForEnergy,
        public int $energyWindowSize,
    ){}
}