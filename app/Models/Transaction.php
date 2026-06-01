<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'prahari_id',
        'challan_id',
        'amount_paid',
        'bank_account_number',
        'status',
    ];

    public function prahari()
    {
        return $this->belongsTo(Prahari::class);
    }

    public function challan()
    {
        return $this->belongsTo(Challan::class);
    }
}
