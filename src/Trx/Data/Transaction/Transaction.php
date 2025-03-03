<?php

declare(strict_types=1);

namespace Trx\Data\Transaction;

final readonly class Transaction
{
    public function __construct(
        public string $transactionId,
        public TokenInfo $tokenInfo,
        public int $blockTimestamp,
        public string $from,
        public string $to,
        public string $type,
        public string $value
    ) {}

    /**
     * @param array $data
     * @return Transaction
     */
    public static function fromJson(array $data): Transaction
    {
        $info = $data['token_info'];

        return new self(
            $data['transaction_id'],
            new TokenInfo(
                symbol: $info['symbol'],
                address: $info['address'],
                decimals: $info['decimals'],
                name: $info['name'],
            ),
            $data['block_timestamp'],
            $data['from'],
            $data['to'],
            $data['type'],
            $data['value'],
        );
    }
}