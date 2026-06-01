<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    protected $fillable = ['key', 'value'];

    protected static $cache = null;

    public static function get($key, $default = null)
    {
        if (self::$cache === null) {
            self::$cache = self::pluck('value', 'key')->toArray();
        }
        return array_key_exists($key, self::$cache) ? self::$cache[$key] : $default;
    }
}
