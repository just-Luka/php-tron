<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1;

use Trx\Domain\Exceptions\ApiRequestException;

abstract class BaseTronGrid
{
    private const MAIN_NET = 'https://api.trongrid.io/v1';
    private const SHASTA_TESTNET = 'https://api.shasta.trongrid.io/v1';
    private static ?string $apiKey;
    private static bool $testmode;

    /**
     * @param string|null $apiKey
     * @return void
     */
    public static function setApiKey(?string $apiKey): void
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @param bool $testmode
     * @return void
     */
    public static function setTestmode(bool $testmode): void
    {
        self::$testmode = $testmode;
    }

    /**
     * @return string
     */
    protected abstract function slug(): string;

    /**
     * @return string
     */
    protected function endpoint(): string
    {
        $url = self::$testmode ? self::SHASTA_TESTNET : self::MAIN_NET;
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

        if (isset(self::$apiKey)) {
            $headers[] = 'TRON-PRO-API-KEY: '.self::$apiKey;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiURL());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = json_decode(curl_exec($ch), true);

        if ($response['success'] === false) {
            throw new ApiRequestException($response['error'], $response['statusCode']);
        }

        curl_close($ch);

        return $response;
    }
}