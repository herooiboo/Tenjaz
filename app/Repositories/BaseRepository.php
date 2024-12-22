<?php

namespace App\Repositories;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * BaseRepository is an abstract class providing common database operations.
 * It acts as a base for all repositories that interact with Eloquent models.
 */
abstract class BaseRepository
{
    /**
     * The model instance being operated on.
     *
     * @var Model|Authenticatable
     */
    protected Model|Authenticatable $model;

    /**
     * Constructor to initialize the repository with a specific model.
     *
     * @param Model|Authenticatable $model
     */
    public function __construct(Model|Authenticatable $model)
    {
        $this->model = $model;
    }

    /**
     * Set the model for the repository.
     *
     * @param Model|Authenticatable $model
     * @return BaseRepository The current repository instance for method chaining.
     */
    public function setModel(Model|Authenticatable $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get the current model instance.
     *
     * @return Model|Authenticatable The current model instance.
     */
    public function getModel(): Model|Authenticatable
    {
        return $this->model;
    }

    /**
     * Retrieve all records from the model.
     *
     * @return Collection A collection of all model records.
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Find a record by a specific field and value.
     *
     * @param string $field The field to search by.
     * @param mixed $value The value to match.
     * @return Model|Authenticatable The matching record.
     */
    public function findByField(string $field, mixed $value): Model|Authenticatable
    {
        return $this->model->where($field, $value)->firstOrFail();
    }

    /**
     * Paginate the model's records.
     *
     * @param int $limit The number of records per page (default: 10).
     * @return LengthAwarePaginator The paginated records.
     */
    public function paginate(int $limit = 10): LengthAwarePaginator
    {
        return $this->model->paginate($limit);
    }

    /**
     * Retrieve a record by its ID.
     *
     * @param int $id The ID of the record to retrieve.
     * @return Model The matching record.
     */
    public function getById(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new record in the model.
     *
     * @param array $data The data for the new record.
     * @return Model The created record.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing record by its ID.
     *
     * @param int $id The ID of the record to update.
     * @param array $data The data to update.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(int $id, array $data): bool
    {
        $entity = $this->model->findOrFail($id);
        return $entity->update($data);
    }

    /**
     * Delete a record by its ID.
     *
     * @param int $id The ID of the record to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(int $id): bool
    {
        $entity = $this->model->findOrFail($id);
        return $entity->delete();
    }
}
