<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Scope untuk pencarian berdasarkan nama
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }

    /**
     * Hubungan dengan buku
     */
    public function books()
    {

        return $this->belongsToMany(Book::class);
    }

    /**
     * Accessor untuk mendapatkan jumlah buku dalam kategori
     */
    public function getBooksCountAttribute()
    {
        // Akan diimplementasikan setelah model Book dibuat
        // return $this->books()->count();
        return 0;
    }

    /**
     * Mutator untuk mengatur slug otomatis dari nama
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($value);
    }

    /**
     * Slug route
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}