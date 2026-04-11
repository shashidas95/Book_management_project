<?php

namespace App\Services;

use App\Domain\Entities\Book as BookEntity;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Services\Contracts\BookServiceInterface;
use App\Exceptions\BookNotFoundException;
use App\Exceptions\BookNotAvailableException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class BookService implements BookServiceInterface
{
    public function __construct(
        protected BookRepositoryInterface $bookRepository
    ) {}

    public function getAllBooks(): Collection
    {
        return $this->bookRepository->getAll();
    }

    public function getBookById(int $id): ?BookEntity
    {
        $book = $this->bookRepository->getById($id);
        if (!$book) {
            throw new BookNotFoundException();
        }
        return $book;
    }

    public function createBook(array $data): BookEntity
    {
        return DB::transaction(fn() => $this->bookRepository->create($data));
    }

    public function updateBook(int $id, array $data): BookEntity
    {
        // We verify existence before attempting update
        $this->getBookById($id);

        return DB::transaction(fn() => $this->bookRepository->update($id, $data));
    }

    public function deleteBook(int $id): void
    {
        $this->getBookById($id);

        DB::transaction(fn() => $this->bookRepository->delete($id));
    }

    public function borrowBook(int $id): bool
    {
        $book = $this->getBookById($id);

        // Use the Domain Entity logic we wrote earlier!
        if (!$book->canBeBorrowed()) {
            throw new BookNotAvailableException();
        }

        return DB::transaction(fn() => $this->bookRepository->borrow($id));
    }

    public function returnBook(int $id): bool
    {
        $this->getBookById($id);

        return DB::transaction(fn() => $this->bookRepository->return($id));
    }

    public function isAvailable(int $id): bool
    {
        $book = $this->bookRepository->getById($id);
        return $book ? $book->canBeBorrowed() : false;
    }
}
