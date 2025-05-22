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
    
    /**
     * Récupère les images du produit sous forme de tableau
     */
    public function getImagesAttribute()
    {
        return $this->image ? json_decode($this->image) : [];
    }
    
    /**
     * Récupère la première image du produit pour l'affichage en miniature
     */
    public function getThumbnailAttribute()
    {
        $images = $this->getImagesAttribute();
        return !empty($images) ? $images[0] : null;
    }
}
