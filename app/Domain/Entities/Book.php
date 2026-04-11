<?php

namespace App\Domain\Entities;

class Book
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $author,
        public string $isbn,
        public ?int $publishedYear,
        public int $availableCopies,
        public int $totalCopies,
        public bool $isActive
    ) {}

    /**
     * Business Logic: Centralized rule for borrowing.
     */
    public function canBeBorrowed(): bool
    {
        return $this->isActive && $this->availableCopies > 0;
    }

    /**
     * Business Logic: Can we return this book?
     */
    public function canBeReturned(): bool
    {
        return $this->availableCopies < $this->totalCopies;
    }
}
