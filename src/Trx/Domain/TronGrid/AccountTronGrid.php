<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid;

use Trx\Data\Account\Account;
use Trx\Domain\Exceptions\ApiRequestException;
use Trx\Domain\Exceptions\InvalidTronAddressException;
use Trx\Domain\Facades\Validator;

class AccountTronGrid extends BaseTronGrid
{
    private const SLUG = "/v1/accounts";

    /**
     * @param string $address
     * @return Account
     * @throws ApiRequestException
     * @throws InvalidTronAddressException
     */
    public function explore(string $address): Account
    {
        return Account::fromJson($this->fetchAccount($address));
    }

    /**
     * @param string $address
     * @return string
     * @throws ApiRequestException
     * @throws InvalidTronAddressException
     */
    protected function fetchAccount(string $address): string
    {
        if (! Validator::isTrxBase58($address)) {
            throw new InvalidTronAddressException('Trx address is not valid');
        }

        $apiURL = self::$apiURL . self::SLUG . "/$address";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiURL);
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