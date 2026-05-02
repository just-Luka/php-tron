<?php

declare(strict_types=1);

namespace Trx\Data;

final readonly class Block
{
    public function __construct(
        public array $ret,
        public array $signature,
        public string $txID,
        public int $netUsage,
        public string $rawDataHex,
        public int $netFee,
        public int $energyUsage,
        public int $blockNumber,
        public int $blockTimestamp,
        public int $energyFee,
        public int $energyUsageTotal,
        public ?int $contractBalance,
        public ?string $contractResource,
        public ?string $contractReceiverAddress,
        public ?string $contractOwnerAddress,
        public string $refBlockBytes,
        public string $refBlockHash,
        public int $expiration,
        public int $timestamp,
        public array $internalTransactions,
    ) {}


    /**
     * @param array $data
     * @return Block
     */
    public static function fromJson(array $data): Block
    {
        $raw      = $data['raw_data'];
        $contract = $raw['contract'][0]['parameter']['value'] ?? [];

        return new self(
            $data['ret'] ?? [],
            $data['signature'] ?? [],
            $data['txID'] ?? '',
            $data['net_usage'] ?? 0,
            $data['raw_data_hex'] ?? '',
            $data['net_fee'] ?? 0,
            $data['energy_usage'] ?? 0,
            (int) ($data['blockNumber'] ?? 0),
            (int) ($data['block_timestamp'] ?? 0),
            $data['energy_fee'] ?? 0,
            $data['energy_usage_total'] ?? 0,
            $contract['balance'] ?? null,
            $contract['resource'] ?? null,
            $contract['receiver_address'] ?? null,
            $contract['owner_address'] ?? null,
            $raw['ref_block_bytes'] ?? '',
            $raw['ref_block_hash'] ?? '',
            $raw['expiration'] ?? 0,
            $raw['timestamp'] ?? 0,
            $data['internal_transactions'] ?? [],
        );
    }
}
