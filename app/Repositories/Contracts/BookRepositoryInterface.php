<?php

namespace App\Repositories\Contracts;

use App\Domain\Entities\Book as BookEntity;
use Illuminate\Support\Collection;

interface BookRepositoryInterface
{
    /**
     * Get all books for the current tenant.
     * @return Collection<int, BookEntity>
     */
    public function getAll(): Collection;

    /**
     * Get single book by ID.
     */
    public function getById(int $id): ?BookEntity;

    /**
     * Create a new book.
     */
    public function create(array $data): BookEntity;

    /**
     * Update an existing book.
     */
    public function update(int $id, array $data): BookEntity;

    /**
     * Delete a book.
     */
    public function delete(int $id): void;

    /**
     * Decrease available copies.
     */
    public function borrow(int $id): bool;

    /**
     * Increase available copies.
     */
    public function return(int $id): bool;

    /**
     * Check if book is available to borrow.
     */
    public function isAvailable(int $id): bool;
}
