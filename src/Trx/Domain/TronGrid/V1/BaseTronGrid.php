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