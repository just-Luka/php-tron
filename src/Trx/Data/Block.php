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
        $raw = $data['raw_data'];

        return new self(
            $data['ret'],
            $data['signature'],
            $data['txID'],
            $data['net_usage'],
            $data['raw_data_hex'],
            $data['net_fee'],
            $data['energy_usage'],
            (int) $data['blockNumber'], # API returns sometimes int and string ...
            (int) $data['block_timestamp'],
            $data['energy_fee'],
            $data['energy_usage_total'],
            $raw['contract'][0]['parameter']['value']['balance'] ?? null,
            $raw['contract'][0]['parameter']['value']['resource'] ?? null,
            $raw['contract'][0]['parameter']['value']['receiver_address'] ?? null,
            $raw['contract'][0]['parameter']['value']['owner_address'] ?? null,
            $raw['ref_block_bytes'],
            $raw['ref_block_hash'],
            $raw['expiration'],
            $raw['timestamp'],
            $data['internal_transactions'],
        );
    }
}