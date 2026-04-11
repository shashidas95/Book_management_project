<?php

namespace App\Repositories;

use App\Models\Book as EloquentBook;
use App\Domain\Entities\Book as BookEntity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{
    public function __construct(protected EloquentBook $book) {}

    /**
     * Translates an Eloquent Model into a Domain Entity.
     * This is the "secret sauce" of professional repositories.
     */
    private function toDomain(EloquentBook $elBook): BookEntity
    {
        return new BookEntity(
            id: $elBook->id,
            title: $elBook->title,
            author: $elBook->author,
            isbn: $elBook->isbn,
            publishedYear: $elBook->published_year,
            availableCopies: $elBook->available_copies,
            totalCopies: $elBook->total_copies,
            isActive: (bool) $elBook->is_active
        );
    }

    public function getAll(): Collection
    {
        Log::info('BookRepository: Fetching all active books for tenant.');
        $tenantId = app('current_tenant_id');

        return $this->book
            ->where('library_id', $tenantId)
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(fn($elBook) => $this->toDomain($elBook));
    }

    public function getById(int $id): ?BookEntity
    {
        Log::info("BookRepository: Finding book ID: {$id}");
        $elBook = $this->book->find($id);

        return $elBook ? $this->toDomain($elBook) : null;
    }

    public function create(array $data): BookEntity
    {
        Log::info("BookRepository: Creating new book");

        // Ensure library_id is set from the current tenant context
        $data['library_id'] = app('current_tenant_id');

        $elBook = $this->book->create($data);
        return $this->toDomain($elBook);
    }

    public function update(int $id, array $data): BookEntity
    {
        Log::info("BookRepository: Updating book ID: {$id}");

        $elBook = $this->book->findOrFail($id);
        $elBook->update($data);

        return $this->toDomain($elBook->fresh());
    }

    public function delete(int $id): void
    {
        Log::info("BookRepository: Deleting book ID: {$id}");
        $this->book->destroy($id);
    }

    public function borrow(int $id): bool
    {
        $elBook = $this->book->find($id);

        if (!$elBook || $elBook->available_copies < 1 || !$elBook->is_active) {
            return false;
        }

        Log::info("BookRepository: Borrowing book ID: {$id}");
        return $elBook->decrement('available_copies', 1);
    }

    public function return(int $id): bool
    {
        $elBook = $this->book->find($id);

        if (!$elBook) {
            return false;
        }

        // Logic check: Don't return more than we actually own
        if ($elBook->available_copies >= $elBook->total_copies) {
            return false;
        }

        Log::info("BookRepository: Returning book ID: {$id}");
        return $elBook->increment('available_copies', 1);
    }
    /**
     * Check if a book is available to be borrowed.
     */
    // public function isAvailable(int $id): bool
    // {
    //     $elBook = $this->book->find($id);

    //     // A book is available only if it exists, is active, and has copies
    //     return $elBook
    //         && $elBook->is_active
    //         && $elBook->available_copies > 0;
    // }
    public function isAvailable(int $id): bool
    {
        $book = $this->getById($id); // This returns a BookEntity
        return $book ? $book->canBeBorrowed() : false;
    }
}
