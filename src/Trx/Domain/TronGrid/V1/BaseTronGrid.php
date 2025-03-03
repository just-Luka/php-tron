<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1;

use Trx\Domain\Exceptions\ApiRequestException;

abstract readonly class BaseTronGrid
{
    private const MAIN_NET = 'https://api.trongrid.io/v1';
    private const SHASTA_TESTNET = 'https://api.shasta.trongrid.io/v1';

    public function __construct(
        private ?string $apiKey = null,
        private bool $testMode = false,
    ) {
    }

    protected abstract function slug(): string;

    /**
     * @return string
     */
    protected function endpoint(): string
    {
        $url = $this->testMode ? self::SHASTA_TESTNET : self::MAIN_NET;
        return $url . $this->slug();
    }

    /**
     * @param \Closure $apiURL
     * @return string
     * @throws ApiRequestException
     */
    protected function fetch(\Closure $apiURL): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiURL());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new ApiRequestException("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}