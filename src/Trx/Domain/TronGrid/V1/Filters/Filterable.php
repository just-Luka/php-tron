<?php

declare(strict_types=1);

namespace Trx\Domain\TronGrid\V1\Filters;

trait Filterable
{
    private array $filters = [];

    /**
     * Only return confirmed transactions.
     * Cannot be used with filterOnlyUnconfirmed().
     */
    public function filterOnlyConfirmed(): static
    {
        $this->filters['only_confirmed'] = 'true';
        return $this;
    }

    /**
     * Only return unconfirmed transactions.
     * Cannot be used with filterOnlyConfirmed().
     */
    public function filterOnlyUnconfirmed(): static
    {
        $this->filters['only_unconfirmed'] = 'true';
        return $this;
    }

    /**
     * Number of transactions per page, default 20, max 200.
     */
    public function filterLimit(int $limit): static
    {
        $this->filters['limit'] = $limit;
        return $this;
    }

    /**
     * Fingerprint of the last transaction returned by the previous page.
     */
    public function filterFingerprint(string $fingerprint): static
    {
        $this->filters['fingerprint'] = $fingerprint;
        return $this;
    }

    /**
     * Contract address in base58 or hex.
     */
    public function filterContractAddress(string $contractAddress): static
    {
        $this->filters['contract_address'] = $contractAddress;
        return $this;
    }

    /**
     * block_timestamp,asc | block_timestamp,desc (default)
     */
    public function filterOrderBy(string $orderBy): static
    {
        $this->filters['order_by'] = $orderBy;
        return $this;
    }

    /**
     * Minimum block_timestamp, default 0.
     */
    public function filterMinTimestamp(string $minTimestamp): static
    {
        $this->filters['min_timestamp'] = $minTimestamp;
        return $this;
    }

    /**
     * Maximum block_timestamp, default now.
     */
    public function filterMaxTimestamp(string $maxTimestamp): static
    {
        $this->filters['max_timestamp'] = $maxTimestamp;
        return $this;
    }

    /**
     * If true, only transactions to this address.
     */
    public function filterOnlyTo(): static
    {
        $this->filters['only_to'] = 'true';
        return $this;
    }

    /**
     * If true, only transactions from this address.
     */
    public function filterOnlyFrom(): static
    {
        $this->filters['only_from'] = 'true';
        return $this;
    }

    protected function toQuery(): string
    {
        return http_build_query($this->filters);
    }
}
