<?php

declare(strict_types=1);

namespace Unit\Data;

use PHPUnit\Framework\TestCase;
use Trx\Data\Account\Account;

class AccountTest extends TestCase
{
    private function validPayload(): array
    {
        return [
            'owner_permission'           => ['threshold' => 1],
            'active_permission'          => [['threshold' => 2]],
            'account_resource'           => [
                'energy_window_optimized' => true,
                'energy_window_size'      => 28800,
            ],
            'address'                    => 'TArTGnzDU3d2oguGq8cmHVpts5ab2y4biW',
            'create_time'                => 1600000000000,
            'balance'                    => 5000000,
            'net_window_size'            => 28800,
            'trc20'                      => [],
        ];
    }

    public function testFromJsonParsesAllFields(): void
    {
        $account = Account::fromJson($this->validPayload());

        $this->assertSame(1, $account->ownerPermissionThreshold);
        $this->assertSame(2, $account->activePermissionThreshold);
        $this->assertSame('TArTGnzDU3d2oguGq8cmHVpts5ab2y4biW', $account->address);
        $this->assertSame(5000000, $account->balance);
        $this->assertSame(28800, $account->netWindowSize);
        $this->assertTrue($account->resource->energyWindowOptimized);
    }

    public function testFromJsonMissingOwnerPermissionDefaultsToZero(): void
    {
        $payload = $this->validPayload();
        unset($payload['owner_permission']);

        $account = Account::fromJson($payload);

        $this->assertSame(0, $account->ownerPermissionThreshold);
    }

    public function testFromJsonMissingActivePermissionDefaultsToZero(): void
    {
        $payload = $this->validPayload();
        unset($payload['active_permission']);

        $account = Account::fromJson($payload);

        $this->assertSame(0, $account->activePermissionThreshold);
    }

    public function testFromJsonMissingTrc20DefaultsToEmptyArray(): void
    {
        $payload = $this->validPayload();
        unset($payload['trc20']);

        $account = Account::fromJson($payload);

        $this->assertSame([], $account->trc20);
    }

    public function testFromJsonOptionalTimestampsAreNull(): void
    {
        $account = Account::fromJson($this->validPayload());

        $this->assertNull($account->latestConsumeTime);
        $this->assertNull($account->netUsage);
        $this->assertNull($account->latestOperationTime);
        $this->assertNull($account->latestConsumeFreeTime);
    }

    public function testFromJsonOptionalEnergyFieldsAreNull(): void
    {
        $account = Account::fromJson($this->validPayload());

        $this->assertNull($account->resource->energyUsage);
        $this->assertNull($account->resource->latestConsumeTimeForEnergy);
    }
}
