<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = ['name', 'slug', 'address'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
