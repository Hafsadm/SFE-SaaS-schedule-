<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type'
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public function getValueAttribute($value)
    {
        // Convertir automatiquement les valeurs booléennes
        if ($value === 'true') return true;
        if ($value === 'false') return false;
        
        return $value;
    }

    public function setValueAttribute($value)
    {
        // Convertir les booléens en string pour le stockage
        if (is_bool($value)) {
            $this->attributes['value'] = $value ? 'true' : 'false';
        } else {
            $this->attributes['value'] = $value;
        }
    }
}
