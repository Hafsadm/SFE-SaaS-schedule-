<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'day_of_week',
        'is_closed',
        'is_holiday',
        'time_slots',
        'exception_date',
        'exception_reason'
    ];

    protected $casts = [
        'time_slots' => 'array',
        'is_closed' => 'boolean',
        'is_holiday' => 'boolean',
        'exception_date' => 'date',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function getDayName()
    {
        $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche',
        ];

        return $days[$this->day_of_week] ?? $this->day_of_week;
    }
}