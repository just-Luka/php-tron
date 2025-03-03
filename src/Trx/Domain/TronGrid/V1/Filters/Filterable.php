<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1\Filters;

trait Filterable
{
    /**
     * true | false. If false, it returns both confirmed and unconfirmed transactions.
     * If no param is specified, it returns both confirmed and unconfirmed transactions.
     * Cannot be used at the same time with only_unconfirmed param.
     *
     * @var bool
     */
    protected readonly string $onlyConfirmed;

    /**
     * true | false. If false, it returns both confirmed and unconfirmed transactions.
     * If no param is specified, it returns both confirmed and unconfirmed transactions.
     * Cannot be used at the same time with only_confirmed param.
     *
     * @var bool
     */
    protected readonly string $onlyUnconfirmed;

    /**
     * number of transactions per page, default 20, max 200
     *
     * @var int
     */
    protected readonly int $limit;

    /**
     * fingerprint of the last transaction returned by the previous page;
     * when using it, the other parameters and filters should remain the same
     *
     * @var string
     */
    protected readonly string $fingerprint;

    /**
     * block_timestamp,asc | block_timestamp,desc (default)
     *
     * @var string
     */
    protected readonly string $orderBy;

    /**
     * minimum block_timestamp, default 0
     *
     * @var \DateTime
     */
    protected readonly string $minTimestamp;

    /**
     * maximum block_timestamp, default now
     *
     * @var \DateTime
     */
    protected readonly string $maxTimestamp;

    /**
     * contract address in base58 or hex
     *
     * @var string
     */
    protected readonly string $contractAddress;

    /**
     * true | false. If true, only transactions to this address, default: false
     *
     * @var bool
     */
    protected readonly string $onlyTo;

    /**
     * true | false. If true, only transactions from this address, default: false
     *
     * @var bool
     */
    protected readonly string $onlyFrom;

    public function filterOnlyConfirmed(): static
    {
        $this->onlyConfirmed = 'true';
        return $this;
    }

    public function filterOnlyUnconfirmed(): static
    {
        $this->onlyUnconfirmed = 'true';
        return $this;
    }

    public function filterLimit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function filterFingerprint(string $fingerprint): static
    {
        $this->fingerprint = $fingerprint;
        return $this;
    }

    public function filterContractAddress(string $contractAddress): static
    {
        $this->contractAddress = $contractAddress;
        return $this;
    }

    public function filterOrderBy(string $orderBy): static
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function filterMinTimestamp(string $minTimestamp): static
    {
        $this->minTimestamp = $minTimestamp;
        return $this;
    }

    public function filterMaxTimestamp(string $maxTimestamp): static
    {
        $this->maxTimestamp = $maxTimestamp;
        return $this;
    }

    public function filterOnlyTo(): static
    {
        $this->onlyTo = 'true';
        return $this;
    }

    public function filterOnlyFrom(): static
    {
        $this->onlyFrom = 'true';
        return $this;
    }

    protected function toQuery(): string
    {
        return http_build_query(
            array_filter(
                array_combine(
                    array_map(fn($key) => strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key)), array_keys(get_object_vars($this))),
                    get_object_vars($this)
                ),
                static fn($value) => $value !== null
            )
        );
    }
}