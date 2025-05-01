<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_available',
    ];

    protected $casts = [
        'price' => 'float',
        'is_available' => 'boolean',
    ];

    /**
     * Relation avec le magasin
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
