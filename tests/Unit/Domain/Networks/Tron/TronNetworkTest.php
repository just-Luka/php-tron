<?php

declare(strict_types=1);

namespace Unit\Domain\Networks\Tron;

use Elliptic\EC;
use kornrunner\Keccak;
use PHPUnit\Framework\TestCase;
use Trx\Data\Wallet;
use Trx\Domain\Facades\Encoder58;
use Trx\Domain\Networks\Tron\TronNetwork;
use Trx\Domain\Networks\Tron\Wallet\TronWallet;
use Trx\TrxClient;

class TronNetworkTest extends TestCase
{
    public function testTronWalletCreationReturnsValidAddress(): void
    {
        $client = new TrxClient();
        $wallet = $client->wallet()->create();

        $this->assertInstanceOf(Wallet::class, $wallet);
        $this->assertMatchesRegularExpression(
            '/^T[1-9A-HJ-NP-Za-km-z]{33}$/',
            $wallet->getAddress()
        );
        $this->assertNotEmpty($wallet->getPrivateKey());
    }

    public function testTronWalletMultipleCreation(): void
    {
        $client = new TrxClient();

        $this->assertEmpty($client->wallet()->createMultiple(-1));
        $this->assertEmpty($client->wallet()->createMultiple(0));

        $wallets = $client->wallet()->createMultiple(5);
        $this->assertCount(5, $wallets);
        $this->assertIsArray($wallets);
    }

    public function testPrivateKeyIsStableAcrossCalls(): void
    {
        $network = new TronNetwork();

        $this->assertSame($network->privateKey(), $network->privateKey());
    }

    public function testPublicKeyIsStableAcrossCalls(): void
    {
        $network = new TronNetwork();

        $this->assertSame($network->publicKey(), $network->publicKey());
    }

    public function testPrivateKeyIs64HexChars(): void
    {
        $network = new TronNetwork();

        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $network->privateKey());
    }

    public function testPublicKeyIs128HexChars(): void
    {
        $network = new TronNetwork();

        // uncompressed secp256k1 public key without the 04 prefix = 64 bytes = 128 hex chars
        $this->assertMatchesRegularExpression('/^[0-9a-f]{128}$/', $network->publicKey());
    }

    public function testWalletAddressDerivesFromStoredPrivateKey(): void
    {
        $network = new TronNetwork();
        $wallet  = (new TronWallet($network))->create();

        // Re-derive the address from the stored private key and verify it matches
        $keyPair    = (new EC('secp256k1'))->keyFromPrivate($wallet->getPrivateKey());
        $publicKey  = substr($keyPair->getPublic(false, 'hex'), 2);
        $addressBin = Encoder58::addressBin($publicKey);
        $checksum   = substr(hash('sha256', hash('sha256', $addressBin, true), true), 0, 4);
        $expected   = Encoder58::encodeBase58(bin2hex($addressBin . $checksum));

        $this->assertSame($expected, $wallet->getAddress());
    }

    public function testEachNetworkInstanceHasUniquePrivateKey(): void
    {
        $a = new TronNetwork();
        $b = new TronNetwork();

        $this->assertNotSame($a->privateKey(), $b->privateKey());
    }

    /**
     * Known-good vector: private key → expected TRON address via Keccak-256.
     * Verify with any standard TRON wallet (e.g. TronLink) using this private key.
     *
     * Private key : da146374a75310b9666e834ee4ad0866d6f4035967bfc76217c5a495fff9f0d0
     * Expected    : TPL66VK2gCXNCD7EJg9pgJRfqcRazjhUZY
     * Wrong (SHA3): TAFWVZEb5Em38Ey2Y4EGXA9tQjTiyykrBN  ← what the old code produced
     */
    public function testKnownPrivateKeyProducesCorrectTronAddress(): void
    {
        $privateKey  = 'da146374a75310b9666e834ee4ad0866d6f4035967bfc76217c5a495fff9f0d0';
        $expected    = 'TPL66VK2gCXNCD7EJg9pgJRfqcRazjhUZY';
        $wrongSha3   = 'TAFWVZEb5Em38Ey2Y4EGXA9tQjTiyykrBN';

        $keyPair    = (new EC('secp256k1'))->keyFromPrivate($privateKey);
        $publicKey  = substr($keyPair->getPublic(false, 'hex'), 2);
        $addressBin = Encoder58::addressBin($publicKey);
        $checksum   = substr(hash('sha256', hash('sha256', $addressBin, true), true), 0, 4);
        $address    = Encoder58::encodeBase58(bin2hex($addressBin . $checksum));

        $this->assertSame($expected, $address, 'Address must be derived using Keccak-256, not SHA3-256');
        $this->assertNotSame($wrongSha3, $address, 'Must not produce the SHA3-256 (wrong) address');
    }

    public function testKeccak256AndSha3256ProduceDifferentHashes(): void
    {
        $input = 'arbitrary input to confirm algorithm difference';

        $keccak = Keccak::hash($input, 256);
        $sha3   = hash('sha3-256', $input);

        $this->assertNotSame($keccak, $sha3, 'Keccak-256 and SHA3-256 must produce different outputs');
    }
}
