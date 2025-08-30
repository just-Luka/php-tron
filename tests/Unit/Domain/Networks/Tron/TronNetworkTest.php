<?php

declare(strict_types=1);

namespace Unit\Domain\Networks\Tron;

use PHPUnit\Framework\TestCase;
use Trx\Data\Wallet;
use Trx\TrxClient;

class TronNetworkTest extends TestCase
{
    public function testTronWalletCreation(): void
    {
        $client = new TrxClient();
        $wallet = $client->wallet()->create();

        $this->assertInstanceOf(Wallet::class, $wallet);

        if (! is_null($wallet->getAddress())) {
            $this->assertMatchesRegularExpression(
                '/^T[1-9A-HJ-NP-Za-km-z]{33}$/',
                $wallet->getAddress()
            );
        }

        $this->assertNotEmpty($wallet->getPrivateKey());
    }

    public function testTronWalletMultipleCreation(): void
    {
        $client = new TrxClient();

        $wallet = $client->wallet()->createMultiple(-1);
        $this->assertEmpty($wallet);

        $wallet = $client->wallet()->createMultiple(0);
        $this->assertEmpty($wallet);

        $wallet = $client->wallet()->createMultiple(5);
        $this->assertCount(5, $wallet);

        $this->assertIsArray($wallet);
    }
}