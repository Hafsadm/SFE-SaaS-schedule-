<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'day_of_week',
        'is_closed',
        'time_slots'
    ];

    protected $casts = [
        'time_slots' => 'array',
        'is_closed' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Retourne le nom français du jour de la semaine
     */
    public function getDayName()
    {
        $frenchDays = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche'
        ];
        
        return $frenchDays[$this->day_of_week] ?? $this->day_of_week;
    }
}