<?php

namespace Trx\Domain\Contracts;

/**
 * Interface Generatable
 *
 * Implementation helps you generate a new crypto wallet,
 * with public and private key.
 *
 */
interface Generatable
{
    public function publicKey(): string;
    public function privateKey(): string;
}