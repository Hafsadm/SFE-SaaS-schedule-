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
        'type',
        'user_id'  // AJOUT: Pour lier les paramètres à un utilisateur spécifique
    ];

    protected $casts = [
        'value' => 'string',
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    // Méthode statique pour récupérer les paramètres d'un utilisateur
    public static function getUserSettings($userId, $keys = [])
    {
        $query = self::where('user_id', $userId);
        
        if (!empty($keys)) {
            $query->whereIn('key', $keys);
        }
        
        return $query->pluck('value', 'key')->toArray();
    }

    // Méthode statique pour mettre à jour un paramètre utilisateur
    public static function setUserSetting($userId, $key, $value)
    {
        return self::updateOrCreate(
            ['user_id' => $userId, 'key' => $key],
            ['value' => $value]
        );
    }
}
