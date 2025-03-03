<?php

declare(strict_types=1);

namespace Trx\Data;

final readonly class Asset
{
    public function __construct(
        public int $id,
        public string $abbr,
        public string $description,
        public string $name,
        public int $num,
        public int $precision,
        public string $url,
        public int $totalSupply,
        public int $trxNum,
        public int $voteScore,
        public string $ownerAddress,
        public int $startTime,
        public int $endTime,
    ) {}

    /**
     * @param array $data
     * @return Asset
     */
    public static function fromJson(array $data): Asset
    {
        return new self(
            $data['id'],
            $data['abbr'],
            $data['description'],
            $data['name'],
            $data['num'],
            $data['precision'],
            $data['url'],
            $data['total_supply'],
            $data['trx_num'],
            $data['vote_score'],
            $data['owner_address'],
            $data['start_time'],
            $data['end_time'],
        );
    }
}