<?php

declare(strict_types=1);

namespace Unit\Domain\Facades;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Trx\Domain\Facades\Encoder58;

class Base58EncoderTest extends TestCase
{
    public function testPreservesAllLeadingZeros(): void
    {
        $hex = '000000';
        $result = Encoder58::encodeBase58($hex);

        $this->assertStringStartsWith('111', $result);
        $this->assertSame(3, strspn($result, '1'));
    }

    public function testThrowsOnInvalidHex(): void
    {
        $this->expectException(\Throwable::class);
        Encoder58::encodeBase58('zzzz');
    }

    #[DataProvider('validPublicKeyProvider')]
    public function testAddressBinGeneratesExpectedLength(string $publicKey): void
    {
        $bin = Encoder58::addressBin($publicKey);

        $this->assertSame(21, strlen($bin), "Address must be 21 bytes long");
    }

    public static function validPublicKeyProvider(): array
    {
        return [
            'uncompressed key' => [
                '04' . str_repeat('11', 64),
            ],
            'compressed key' => [
                '02' . str_repeat('aa', 32),
            ],
            'short key' => [
                'abcd',
            ],
        ];
    }

    public function testInvalidHexReturnsEmptyString(): void
    {
        $result = Encoder58::addressBin('zzzzzz');
        $this->assertSame('', $result, "Invalid hex must return empty string");
    }

    public function testDeterministicOutput(): void
    {
        $publicKey = '04' . str_repeat('01', 64); // 65-byte hex key
        $bin1 = Encoder58::addressBin($publicKey);
        $bin2 = Encoder58::addressBin($publicKey);

        $this->assertSame($bin1, $bin2);
    }

    public function testPrefixIs41(): void
    {
        $publicKey = '04' . str_repeat('22', 64);
        $bin = Encoder58::addressBin($publicKey);

        // First byte should always be 0x41
        $this->assertSame("\x41", $bin[0]);
    }
}