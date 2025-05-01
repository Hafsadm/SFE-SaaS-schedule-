<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'holiday_date',
        'holiday_name'
    ];

    protected $casts = [
        'holiday_date' => 'date',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}