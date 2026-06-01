<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cases extends Model
{
    use HasFactory;
    protected $fillable = [
        'prahari_id',
        'category_id',      
        'vehicle_number',
        'location',
        'evidence_file',
        'status',
        'violation_datetime',
    ];

    public function prahari()
    {
        return $this->belongsTo(Prahari::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function challan()
    {
        return $this->hasOne(Challan::class, 'case_id');
    }
}
