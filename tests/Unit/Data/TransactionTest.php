<?php

declare(strict_types=1);

namespace Unit\Data;

use PHPUnit\Framework\TestCase;
use Trx\Data\Transaction\Transaction;

class TransactionTest extends TestCase
{
    private function validPayload(): array
    {
        return [
            'transaction_id'  => 'abc123txid',
            'block_timestamp' => 1700000000000,
            'from'            => 'TFrom1234567890123456789012345678',
            'to'              => 'TTo12345678901234567890123456789012',
            'type'            => 'Transfer',
            'value'           => '1000000',
            'token_info'      => [
                'symbol'   => 'USDT',
                'address'  => 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t',
                'decimals' => 6,
                'name'     => 'Tether USD',
            ],
        ];
    }

    public function testFromJsonParsesAllFields(): void
    {
        $tx = Transaction::fromJson($this->validPayload());

        $this->assertSame('abc123txid', $tx->transactionId);
        $this->assertSame(1700000000000, $tx->blockTimestamp);
        $this->assertSame('TFrom1234567890123456789012345678', $tx->from);
        $this->assertSame('TTo12345678901234567890123456789012', $tx->to);
        $this->assertSame('Transfer', $tx->type);
        $this->assertSame('1000000', $tx->value);
    }

    public function testFromJsonParsesTokenInfo(): void
    {
        $tx = Transaction::fromJson($this->validPayload());

        $this->assertSame('USDT', $tx->tokenInfo->symbol);
        $this->assertSame('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t', $tx->tokenInfo->address);
        $this->assertSame(6, $tx->tokenInfo->decimals);
        $this->assertSame('Tether USD', $tx->tokenInfo->name);
    }

    public function testFromJsonMissingTokenInfoDefaultsToEmptyValues(): void
    {
        $payload = $this->validPayload();
        unset($payload['token_info']);

        $tx = Transaction::fromJson($payload);

        $this->assertSame('', $tx->tokenInfo->symbol);
        $this->assertSame('', $tx->tokenInfo->address);
        $this->assertSame(0, $tx->tokenInfo->decimals);
        $this->assertSame('', $tx->tokenInfo->name);
    }

    public function testFromJsonMissingTopLevelFieldsDefaultToSafeValues(): void
    {
        $payload = $this->validPayload();
        unset($payload['transaction_id'], $payload['from'], $payload['to'],
              $payload['type'], $payload['value'], $payload['block_timestamp']);

        $tx = Transaction::fromJson($payload);

        $this->assertSame('', $tx->transactionId);
        $this->assertSame('', $tx->from);
        $this->assertSame('', $tx->to);
        $this->assertSame('', $tx->type);
        $this->assertSame('', $tx->value);
        $this->assertSame(0, $tx->blockTimestamp);
    }
}
