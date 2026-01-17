<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Scope untuk mendapatkan setting berdasarkan key
     */
    public function scopeKey($query, $key)
    {
        return $query->where('key', $key);
    }

    /**
     * Mendapatkan nilai setting berdasarkan key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Menyimpan atau mengupdate setting
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function setValue($key, $value)
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Mendapatkan semua setting sebagai array asosiatif
     *
     * @return array
     */
    public static function getAllSettings()
    {
        return self::pluck('value', 'key')->toArray();
    }

    /**
     * Accessor untuk mengubah nilai ke tipe yang sesuai
     */
    public function getValueAttribute($value)
    {
        // Coba decode JSON
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // Coba parse sebagai boolean
        if ($value === 'true' || $value === 'false') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        // Coba parse sebagai integer
        if (is_numeric($value)) {
            return (int) $value;
        }

        // Coba parse sebagai float
        if (is_numeric($value) && strpos($value, '.') !== false) {
            return (float) $value;
        }

        // Return sebagai string
        return $value;
    }

    /**
     * Mutator untuk menyimpan nilai sebagai JSON jika array
     */
    public function setValueAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
}