<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'library_id',
        'category_id',
        'title',
        'author',
        'isbn',
        'published_year',
        'total_copies',
        'available_copies',
        'cover_image',
        'is_active'
    ];

    /**
     * Relationship: A book belongs to a library (Tenant)
     */
    public function library()
    {
        return $this->belongsTo(Library::class);
    }

    /**
     * Helper to check availability (used in Service/Repository)
     */
    public function isAvailable(): bool
    {
        return $this->available_copies > 0 && $this->is_active;
    }
}
