<?php

declare(strict_types=1);

namespace Trx;

use Trx\Domain\Clients\TronGridV1;
use Trx\Domain\Clients\TronNet;
use Trx\Domain\TronGrid\V1\BaseTronGrid;

class TrxClient
{
    use TronGridV1, TronNet;

    /**
     * @param string|null $apiKey
     * @param bool $testMode
     */
    public function __construct(
        private readonly ?string $apiKey = null,
        private readonly bool $testMode = false,
    ) {
        BaseTronGrid::setApiKey($this->apiKey);
        BaseTronGrid::setTestMode($this->testMode);
    }
}