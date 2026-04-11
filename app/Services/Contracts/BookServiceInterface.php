<?php

namespace App\Services\Contracts;

use App\Domain\Entities\Book as BookEntity;
use Illuminate\Support\Collection;

interface BookServiceInterface
{
    public function getAllBooks(): Collection;

    public function getBookById(int $id): ?BookEntity;

    public function createBook(array $data): BookEntity;

    public function updateBook(int $id, array $data): BookEntity;

    public function deleteBook(int $id): void;

    public function borrowBook(int $id): bool;

    public function returnBook(int $id): bool;

    public function isAvailable(int $id): bool;
}
