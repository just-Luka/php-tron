<?php

declare(strict_types=1);

namespace Trx\Data\Account;

final readonly class Account
{
    public function __construct(
        public int $ownerPermissionThreshold,
        public int $activePermissionThreshold,
        public AccountResource $resource,
        public string $address,
        public int $createTime,
        public int $latestConsumeTime,
        public int $netUsage,
        public int $latestOperationTime,
        public int $balance,
        public array $trc20,
        public int $latestConsumeFreeTime,
        public int $netWindowSize,
    ){}

    /**
     * @param array $data
     * @return Account
     */
    public static function fromJson(array $data): Account
    {
        $resource = $data['account_resource'];

        return new self(
            $data['owner_permission']['threshold'],
            $data['active_permission'][0]['threshold'],
            new AccountResource(
                energyWindowOptimized: $resource['energy_window_optimized'],
                energyUsage: $resource['energy_usage'],
                latestConsumeTimeForEnergy: $resource['latest_consume_time_for_energy'],
                energyWindowSize: $resource['energy_window_size'],
            ),
            $data['address'],
            $data['create_time'],
            $data['latest_consume_time'],
            $data['net_usage'],
            $data['latest_opration_time'],
            $data['balance'],
            $data['trc20'],
            $data['latest_consume_free_time'],
            $data['net_window_size'],
        );
    }
}