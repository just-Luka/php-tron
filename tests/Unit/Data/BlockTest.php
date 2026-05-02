<?php

declare(strict_types=1);

namespace Unit\Data;

use PHPUnit\Framework\TestCase;
use Trx\Data\Block;

class BlockTest extends TestCase
{
    private function validPayload(): array
    {
        return [
            'ret'                  => [['contractRet' => 'SUCCESS']],
            'signature'            => ['abc123'],
            'txID'                 => 'txid-abc',
            'net_usage'            => 100,
            'raw_data_hex'         => 'deadbeef',
            'net_fee'              => 0,
            'energy_usage'         => 0,
            'blockNumber'          => 12345678,
            'block_timestamp'      => 1700000000000,
            'energy_fee'           => 0,
            'energy_usage_total'   => 0,
            'internal_transactions'=> [],
            'raw_data'             => [
                'contract'       => [[
                    'parameter' => [
                        'value' => [
                            'balance'          => 1000000,
                            'resource'         => 'BANDWIDTH',
                            'receiver_address' => 'TReceiverAddr1234567890123456789',
                            'owner_address'    => 'TOwnerAddr12345678901234567890123',
                        ],
                    ],
                ]],
                'ref_block_bytes' => 'ab12',
                'ref_block_hash'  => 'cd34',
                'expiration'      => 1700000060000,
                'timestamp'       => 1700000000000,
            ],
        ];
    }

    public function testFromJsonParsesAllFields(): void
    {
        $block = Block::fromJson($this->validPayload());

        $this->assertSame('txid-abc', $block->txID);
        $this->assertSame(12345678, $block->blockNumber);
        $this->assertSame(1700000000000, $block->blockTimestamp);
        $this->assertSame(100, $block->netUsage);
        $this->assertSame('deadbeef', $block->rawDataHex);
        $this->assertSame(1000000, $block->contractBalance);
        $this->assertSame('BANDWIDTH', $block->contractResource);
        $this->assertSame('TReceiverAddr1234567890123456789', $block->contractReceiverAddress);
        $this->assertSame('TOwnerAddr12345678901234567890123', $block->contractOwnerAddress);
        $this->assertSame('ab12', $block->refBlockBytes);
        $this->assertSame('cd34', $block->refBlockHash);
    }

    public function testFromJsonBlockNumberCastFromString(): void
    {
        $payload = $this->validPayload();
        $payload['blockNumber'] = '9999999';

        $block = Block::fromJson($payload);

        $this->assertSame(9999999, $block->blockNumber);
    }

    public function testFromJsonBlockTimestampCastFromString(): void
    {
        $payload = $this->validPayload();
        $payload['block_timestamp'] = '1700000000000';

        $block = Block::fromJson($payload);

        $this->assertSame(1700000000000, $block->blockTimestamp);
    }

    public function testFromJsonContractFieldsNullWhenMissing(): void
    {
        $payload = $this->validPayload();
        $payload['raw_data']['contract'][0]['parameter']['value'] = [];

        $block = Block::fromJson($payload);

        $this->assertNull($block->contractBalance);
        $this->assertNull($block->contractResource);
        $this->assertNull($block->contractReceiverAddress);
        $this->assertNull($block->contractOwnerAddress);
    }

    public function testFromJsonContractFieldsNullWhenContractMissing(): void
    {
        $payload = $this->validPayload();
        $payload['raw_data']['contract'] = [];

        $block = Block::fromJson($payload);

        $this->assertNull($block->contractBalance);
        $this->assertNull($block->contractReceiverAddress);
    }

    public function testFromJsonMissingTopLevelFieldsDefaultToSafeValues(): void
    {
        $payload = $this->validPayload();
        unset($payload['net_usage'], $payload['net_fee'], $payload['energy_usage'],
              $payload['energy_fee'], $payload['energy_usage_total'],
              $payload['internal_transactions'], $payload['raw_data_hex']);

        $block = Block::fromJson($payload);

        $this->assertSame(0, $block->netUsage);
        $this->assertSame(0, $block->netFee);
        $this->assertSame(0, $block->energyUsage);
        $this->assertSame(0, $block->energyFee);
        $this->assertSame(0, $block->energyUsageTotal);
        $this->assertSame([], $block->internalTransactions);
        $this->assertSame('', $block->rawDataHex);
    }

    public function testFromJsonMissingRetAndSignatureDefaultToEmptyArrays(): void
    {
        $payload = $this->validPayload();
        unset($payload['ret'], $payload['signature']);

        $block = Block::fromJson($payload);

        $this->assertSame([], $block->ret);
        $this->assertSame([], $block->signature);
    }
}
