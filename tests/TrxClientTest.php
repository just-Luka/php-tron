<?php

declare(strict_types=1);

namespace Trx\Tests;

use PHPUnit\Framework\TestCase;
use Trx\Domain\Exceptions\ApiRequestException;
use Trx\Domain\Exceptions\InvalidTronAddressException;
use Trx\TrxClient;

class TrxClientTest extends TestCase
{
    private array $addresses = [
        'THmK3MJRUPAqiD4qqLKXTQ4scRpF6BbAgg',
        'TTKn7ZZ2mKRuGVYHzseGqU7NGgW5K5NB6Z',
        'TQ83GoBBN9ygy3w2XJUVgAT22esg3QeqXT',
        'TNA1ebPgKbjEBcN3t9Uzp1nXMuTHPGeVnZ',
        'TTA6c9yTjrMt3HLH7sZU2HM4JA75UDfLwe',
        'TMPKyAxaWqk14FWcxWy26acn9eFmvJ6D2m',
        'TG5wUqBkukAho2E38ca3EZG4zvYp3hUivZ',
        'TARPwbeEgjDM3T8Moue49E6inEdDjsZksa',
        'TNGj6v5eYEu44KwCCsizLe3fPux6CSUC79',
        'TV1BR874H5AFwt2FXfgFf95RD9xvy9WFSS',
        'TT2VuYpKUDXGvSnXAkvVVzHTqUvZQPD3kc',
        'TGrx8pzqGUHDhXRaJn5Swn9LXDDKaLQfvZ',
    ];

    /**
     * @throws ApiRequestException
     * @throws InvalidTronAddressException
     */
    public function testClient(): void
    {
        $client = new TrxClient();
        $wallet = $client->wallet()->create();

        var_dump($wallet);
    }
}