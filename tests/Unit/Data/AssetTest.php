<?php

declare(strict_types=1);

namespace Unit\Data;

use PHPUnit\Framework\TestCase;
use Trx\Data\Asset;

class AssetTest extends TestCase
{
    private function validPayload(): array
    {
        return [
            'id'            => 1000001,
            'abbr'          => 'BTT',
            'description'   => 'BitTorrent Token',
            'name'          => 'BitTorrent',
            'num'           => 1,
            'precision'     => 6,
            'url'           => 'https://bittorrent.com',
            'total_supply'  => 990000000000000,
            'trx_num'       => 1,
            'vote_score'    => 0,
            'owner_address' => 'TF5Bn4cJCT6GVeUgyCN4rBhDg42KBrpAjg',
            'start_time'    => 1550000000000,
            'end_time'      => 1660000000000,
        ];
    }

    public function testFromJsonParsesAllFields(): void
    {
        $asset = Asset::fromJson($this->validPayload());

        $this->assertSame(1000001, $asset->id);
        $this->assertSame('BTT', $asset->abbr);
        $this->assertSame('BitTorrent Token', $asset->description);
        $this->assertSame('BitTorrent', $asset->name);
        $this->assertSame(1, $asset->num);
        $this->assertSame(6, $asset->precision);
        $this->assertSame('https://bittorrent.com', $asset->url);
        $this->assertSame(990000000000000, $asset->totalSupply);
        $this->assertSame(1, $asset->trxNum);
        $this->assertSame(0, $asset->voteScore);
        $this->assertSame('TF5Bn4cJCT6GVeUgyCN4rBhDg42KBrpAjg', $asset->ownerAddress);
        $this->assertSame(1550000000000, $asset->startTime);
        $this->assertSame(1660000000000, $asset->endTime);
    }
}
