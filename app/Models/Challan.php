<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    protected $fillable = [
        'prahari_id',
        'case_id',
        'category_id',
        'vehicle_number',
        'amount',
        'status',
        'challan_date',
    ];

    public function prahari()
    {
        return $this->belongsTo(Prahari::class);
    }

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
