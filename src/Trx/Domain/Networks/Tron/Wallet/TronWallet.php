<?php

declare(strict_types=1);

namespace Trx\Domain\Networks\Tron\Wallet;

use Trx\Data\Wallet;
use Trx\Domain\Contracts\Generatable;
use Trx\Domain\Facades\Encoder58;
use Trx\Domain\Facades\Validator;

final readonly class TronWallet
{
    public function __construct(
        private Generatable $generatable
    ) {}

    /**
     * Create a new Wallet
     *
     * @return Wallet
     */
    public function create(): Wallet
    {
        $addressBin = Encoder58::addressBin($this->generatable->publicKey());
        $checksum = substr(
            hash('sha256', hash('sha256', $addressBin, true), true),
            0,
            4,
        );

        $address = Encoder58::encodeBase58(bin2hex($addressBin . $checksum));
        if (! Validator::isTrxBase58($address)) {
            $address = null;
        }

        return new Wallet(
            address: $address,
            privateKey: $this->generatable->privateKey()
        );
    }

    /**
     * Create a new array of multiple Wallets
     *
     * @param int $amount
     * @return array<int, Wallet>
     */
    public function createMultiple(int $amount): array
    {
        return array_map(fn() => $this->create(), range(1, $amount));
    }
}