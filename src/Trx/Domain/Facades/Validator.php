<?php

declare(strict_types=1);

namespace Trx\Domain\Facades;

final readonly class Validator
{
    /**
     * Validates whether address is correct TRX format
     *
     * @param string $address
     * @return bool
     */
    public static function isTrxBase58(string $address): bool
    {
        return preg_match('/^T[1-9A-HJ-NP-Za-km-z]{33}$/', $address) === 1;
    }
}