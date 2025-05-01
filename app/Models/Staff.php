<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'role',
        'bio',
        'image',
        'email',
        'phone',
    ];

    /**
     * Relation avec le magasin
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}

