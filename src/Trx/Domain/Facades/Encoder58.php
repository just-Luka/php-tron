<?php

declare(strict_types=1);

namespace Trx\Domain\Facades;

final readonly class Encoder58
{
    private const TRON_ALPHABIT = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

    /**
     * Encoding...
     *
     * @param string $hex
     * @return string
     */
    public static function encodeBase58(string $hex): string
    {
        $num = gmp_init($hex, 16);

        $base58 = '';
        while (gmp_cmp($num, 0) > 0) {
            list($num, $rem) = gmp_div_qr($num, 58);
            $base58 = self::TRON_ALPHABIT[gmp_intval($rem)] . $base58;
        }

        return array_reduce(
            str_split($hex, 2),
            static fn($carry, $byte) => $byte === '00' ? '1' . $carry : $carry,
            $base58
        );
    }

    /**
     * Tron address to bin
     *
     * @param string $publicKey
     * @return string
     */
    public static function addressBin(string $publicKey): string
    {
        $bin = hex2bin($publicKey);
        if ($bin === false) {
            return '';
        }

        $addressBytes = substr(hash('sha3-256', $bin), -40);
        $tronAddressHex = "41" . $addressBytes;

        return hex2bin($tronAddressHex) ?: '';
    }
}