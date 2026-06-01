<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prahari extends Model
{
    use HasFactory; 
    protected $fillable = [
        'name',
        'aadhar_number',
        'phone',
        'bank_account_number',
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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
