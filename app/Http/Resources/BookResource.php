<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // $this refers to the BookEntity passed to the resource
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'year' => $this->publishedYear,
            'inventory' => [
                'available' => $this->availableCopies,
                'total' => $this->totalCopies,
            ],
            // Conditional Attribute: Only visible if the user is an admin
            // 'inventory' => $this->when($request->user()?->isAdmin(), [
            //     'available' => $this->availableCopies,
            //     'total'     => $this->totalCopies,
            // ]),
            'status' => [
                'active' => $this->isActive,
                'borrowable' => $this->canBeBorrowed(),
            ]
        ];
    }
}
