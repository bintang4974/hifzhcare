<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Find record by ID.
     */
    public function find(int $id): ?Model;

    /**
     * Find record by ID or fail.
     */
    public function findOrFail(int $id): Model;

    /**
     * Get all records.
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Create new record.
     */
    public function create(array $data): Model;

    /**
     * Update existing record.
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete record.
     */
    public function delete(int $id): bool;

    /**
     * Find by specific column.
     */
    public function findBy(string $column, mixed $value): ?Model;

    /**
     * Get records where column matches value.
     */
    public function getWhere(string $column, mixed $value): Collection;

    /**
     * Count all records.
     */
    public function count(): int;
}
