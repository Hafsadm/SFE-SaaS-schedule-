<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exception extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'exception_date',
        'exception_raison',
        'is_closed',
        'time_slots'
    ];

    protected $casts = [
        'time_slots' => 'array',
        'is_closed' => 'boolean',
        'exception_date' => 'date',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}