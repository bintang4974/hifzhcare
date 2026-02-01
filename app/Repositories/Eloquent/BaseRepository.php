<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * Model instance.
     */
    protected Model $model;
    
    /**
     * Constructor.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    /**
     * Find record by ID.
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }
    
    /**
     * Find record by ID or fail.
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }
    
    /**
     * Get all records.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }
    
    /**
     * Get paginated records.
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }
    
    /**
     * Create new record.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }
    
    /**
     * Update existing record.
     */
    public function update(int $id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);
        return $record->fresh();
    }
    
    /**
     * Delete record.
     */
    public function delete(int $id): bool
    {
        $record = $this->findOrFail($id);
        return $record->delete();
    }
    
    /**
     * Find by specific column.
     */
    public function findBy(string $column, mixed $value): ?Model
    {
        return $this->model->where($column, $value)->first();
    }
    
    /**
     * Get records where column matches value.
     */
    public function getWhere(string $column, mixed $value): Collection
    {
        return $this->model->where($column, $value)->get();
    }
    
    /**
     * Count all records.
     */
    public function count(): int
    {
        return $this->model->count();
    }
    
    /**
     * Begin database transaction.
     */
    protected function beginTransaction(): void
    {
        DB::beginTransaction();
    }
    
    /**
     * Commit database transaction.
     */
    protected function commit(): void
    {
        DB::commit();
    }
    
    /**
     * Rollback database transaction.
     */
    protected function rollback(): void
    {
        DB::rollBack();
    }
    
    /**
     * Get fresh model instance.
     */
    protected function getModel(): Model
    {
        return app($this->model::class);
    }
}