<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid;

abstract class BaseTronGrid
{
    private const MAIN_NET = 'https://api.trongrid.io';
    private const SHASTA_TESTNET = 'https://api.shasta.trongrid.io';
    protected static string $apiURL;

    public function __construct(
        private readonly ?string $apiKey = null,
        private readonly bool $testMode = false,
    ) {
        self::$apiURL = $this->testMode ? self::SHASTA_TESTNET : self::MAIN_NET;
    }
}