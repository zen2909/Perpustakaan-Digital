<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'isbn',
        'title',
        'slug',
        'author_id',
        'publisher',
        'published_year',
        'pages',
        'description',
        'cover_image',
        'stock',
        'available_stock'
    ];

    protected $casts = [
        'published_year' => 'integer',
        'stock' => 'integer',
        'available_stock' => 'integer',
    ];
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function isAvailable(): bool
    {
        return $this->available_stock > 0;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}