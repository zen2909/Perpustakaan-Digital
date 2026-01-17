<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'biography',
        'photo',
        'nationality',
        'birth_year',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_year' => 'integer',
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
            ->orWhere('nationality', 'like', "%{$search}%");
    }

    /**
     * Scope untuk filter berdasarkan tahun kelahiran
     */
    public function scopeBirthYearRange($query, $fromYear, $toYear)
    {
        return $query->whereBetween('birth_year', [$fromYear, $toYear]);
    }

    /**
     * Hubungan dengan buku
     */
    public function books()
    {
        // Akan dibuat setelah membuat model Book
        // return $this->hasMany(Book::class);
    }

    /**
     * Accessor untuk mendapatkan jumlah buku yang ditulis
     */
    public function getBooksCountAttribute()
    {
        // Akan diimplementasikan setelah model Book dibuat
        // return $this->books()->count();
        return 0;
    }

    /**
     * Accessor untuk mendapatkan usia penulis
     */
    public function getAgeAttribute()
    {
        if ($this->birth_year) {
            return date('Y') - $this->birth_year;
        }
        return null;
    }

    /**
     * Accessor untuk mendapatkan foto penulis dengan URL lengkap
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
                return $this->photo;
            }
            return asset('storage/' . $this->photo);
        }
        return asset('images/default-author.jpg');
    }
}