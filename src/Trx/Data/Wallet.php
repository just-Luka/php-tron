<?php

declare(strict_types=1);

namespace Trx\Data;

final readonly class Wallet
{
    public function __construct(
        private ?string $address,
        private string $privateKey,
    ) {}

    /**
     * @return ?string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }
}