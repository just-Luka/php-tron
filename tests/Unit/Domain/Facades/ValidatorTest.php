<?php

declare(strict_types=1);

namespace Unit\Domain\Facades;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Trx\Domain\Facades\Validator;

class ValidatorTest extends TestCase
{
    public function testValidTrxBase58(): void
    {
        $this->assertTrue(Validator::isTrxBase58('TArTGnzDU3d2oguGq8cmHVpts5ab2y4biW'));
    }

    #[DataProvider('invalidTrxAddressesProvider')]
    public function testInvalidTrxBase58(string $address): void
    {
        $this->assertFalse(Validator::isTrxBase58($address));
    }

    public static function invalidTrxAddressesProvider(): array
    {
        return [
            'too short'         => ['T123'],
            'too long'          => ['T' . str_repeat('1', 40)],
            'missing T prefix'  => ['A123456789012345678901234567890123'],
            'lowercase prefix'  => ['t123456789012345678901234567890123'],
            'invalid chars'     => ['T12345678901234567890123456789!@#$'],
            'empty string'      => [''],
            'only T'            => ['T'],
        ];
    }
}