<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'amount',
        'description',
        'status',
    ];

    public function cases()
    {
        return $this->hasMany(Cases::class);
    }

    public function challans()
    {
        return $this->hasMany(Challan::class);
    }
}
