<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1;

use Trx\Domain\Exceptions\ApiRequestException;

abstract class BaseTronGrid
{
    private const MAIN_NET = 'https://api.trongrid.io/v1';
    private const SHASTA_TESTNET = 'https://api.shasta.trongrid.io/v1';

    public function __construct(
        private readonly ?string $apiKey,
        private readonly bool $testmode,
    ) {}

    /**
     * @return string
     */
    protected abstract function slug(): string;

    /**
     * @return string
     */
    protected function endpoint(): string
    {
        $url = $this->testmode ? self::SHASTA_TESTNET : self::MAIN_NET;
        return $url . $this->slug();
    }

    /**
     * @param \Closure $apiURL
     * @return array
     * @throws ApiRequestException
     */
    protected function fetch(\Closure $apiURL): array
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        if ($this->apiKey !== null) {
            $headers[] = 'TRON-PRO-API-KEY: ' . $this->apiKey;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiURL());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $raw = curl_exec($ch);

        if ($raw === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new ApiRequestException($error ?: 'cURL request failed', 0);
        }

        curl_close($ch);

        $response = json_decode($raw, true);

        if (isset($response['success']) && $response['success'] === false) {
            throw new ApiRequestException($response['error'] ?? 'Unknown error', $response['statusCode'] ?? 0);
        }

        return $response ?? [];
    }
}
