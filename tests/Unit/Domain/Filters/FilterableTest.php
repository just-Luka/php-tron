<?php

declare(strict_types=1);

namespace Unit\Domain\Filters;

use PHPUnit\Framework\TestCase;
use Trx\Domain\TronGrid\V1\Filters\Filterable;

class FilterableTest extends TestCase
{
    private function makeFilterable(): object
    {
        return new class {
            use Filterable;

            public function query(): string
            {
                return $this->toQuery();
            }
        };
    }

    public function testEmptyQueryWhenNoFiltersSet(): void
    {
        $f = $this->makeFilterable();

        $this->assertSame('', $f->query());
    }

    public function testFilterOnlyConfirmed(): void
    {
        $f = $this->makeFilterable();
        $f->filterOnlyConfirmed();

        $this->assertSame('only_confirmed=true', $f->query());
    }

    public function testFilterOnlyUnconfirmed(): void
    {
        $f = $this->makeFilterable();
        $f->filterOnlyUnconfirmed();

        $this->assertSame('only_unconfirmed=true', $f->query());
    }

    public function testFilterLimit(): void
    {
        $f = $this->makeFilterable();
        $f->filterLimit(50);

        $this->assertSame('limit=50', $f->query());
    }

    public function testFilterFingerprint(): void
    {
        $f = $this->makeFilterable();
        $f->filterFingerprint('fp123');

        $this->assertSame('fingerprint=fp123', $f->query());
    }

    public function testFilterContractAddress(): void
    {
        $f = $this->makeFilterable();
        $f->filterContractAddress('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t');

        $this->assertStringContainsString('TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t', $f->query());
    }

    public function testFilterOrderBy(): void
    {
        $f = $this->makeFilterable();
        $f->filterOrderBy('block_timestamp,asc');

        $this->assertStringContainsString('order_by=', $f->query());
        $this->assertStringContainsString('block_timestamp', $f->query());
    }

    public function testFilterMinTimestamp(): void
    {
        $f = $this->makeFilterable();
        $f->filterMinTimestamp('1700000000000');

        $this->assertSame('min_timestamp=1700000000000', $f->query());
    }

    public function testFilterMaxTimestamp(): void
    {
        $f = $this->makeFilterable();
        $f->filterMaxTimestamp('1800000000000');

        $this->assertSame('max_timestamp=1800000000000', $f->query());
    }

    public function testFilterOnlyTo(): void
    {
        $f = $this->makeFilterable();
        $f->filterOnlyTo();

        $this->assertSame('only_to=true', $f->query());
    }

    public function testFilterOnlyFrom(): void
    {
        $f = $this->makeFilterable();
        $f->filterOnlyFrom();

        $this->assertSame('only_from=true', $f->query());
    }

    public function testChainedFiltersProduceMultipleParams(): void
    {
        $f = $this->makeFilterable();
        $f->filterOnlyConfirmed()->filterLimit(100)->filterOnlyTo();

        parse_str($f->query(), $params);

        $this->assertSame('true', $params['only_confirmed']);
        $this->assertSame('100', $params['limit']);
        $this->assertSame('true', $params['only_to']);
    }

    public function testFilterMethodsReturnStaticForFluentInterface(): void
    {
        $f = $this->makeFilterable();

        $this->assertSame($f, $f->filterOnlyConfirmed());
        $this->assertSame($f, $f->filterLimit(10));
        $this->assertSame($f, $f->filterFingerprint('fp'));
    }

    public function testCallingTheSameFilterTwiceOverwritesPreviousValue(): void
    {
        $f = $this->makeFilterable();
        $f->filterLimit(10)->filterLimit(200);

        parse_str($f->query(), $params);

        $this->assertSame('200', $params['limit']);
    }

    public function testFiltersDoNotLeakUnrelatedClassProperties(): void
    {
        $f = new class {
            use Filterable;

            private string $secret = 'should-not-appear';

            public function query(): string
            {
                return $this->toQuery();
            }
        };

        $f->filterLimit(5);

        $this->assertStringNotContainsString('secret', $f->query());
        $this->assertStringNotContainsString('should-not-appear', $f->query());
    }
}
