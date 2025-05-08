<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'pays',
        'phone',
        'ouvert_jusqua',
        'lien_rdv',
        'latitude',
        'longitude',
        'services' ,
        'image', 
        'exterior_image', 
        'interior_image', 
        'equipment_image', 
        'email',
        'annee_ouverture',
        'site_web',
    ];

    protected $casts = [
        'services' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'annee_ouverture' => 'integer',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function exceptions()
    {
        return $this->hasMany(Exception::class);
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }


    
public function getIsOpenAttribute()
{
    $now = Carbon::now();
    $today = Carbon::today();
    $dayOfWeek = strtolower($today->englishDayOfWeek);
    
    // Vérifier s'il y a une exception pour aujourd'hui
    $exception = $this->exceptions()
        ->whereDate('exception_date', $today)
        ->first();
    
    if ($exception && $exception->is_closed) {
        return false;
    }
    
    // Vérifier s'il y a un jour férié pour aujourd'hui
    $holiday = $this->holidays()
        ->whereDate('holiday_date', $today)
        ->first();
    
    if ($holiday) {
        return false;
    }
    
    // Vérifier l'horaire régulier pour aujourd'hui
    $regularSchedule = $this->schedules()
        ->where('day_of_week', $dayOfWeek)
        ->first();
    
    if ($regularSchedule && $regularSchedule->is_closed) {
        return false;
    }
    
    // Vérifier si le magasin est ouvert selon le champ ouvert_jusqua
    if ($this->ouvert_jusqua) {
        $heureFermeture = Carbon::parse($this->ouvert_jusqua);
        if ($now->lt($heureFermeture)) {
            return true;
        }
    }
    
    // Vérifier aussi les créneaux horaires
    if ($regularSchedule && !$regularSchedule->is_closed && isset($regularSchedule->time_slots) && is_array($regularSchedule->time_slots)) {
        foreach ($regularSchedule->time_slots as $slot) {
            $start = Carbon::parse($slot['start']);
            $end = Carbon::parse($slot['end']);
            
            if ($now->between($start, $end)) {
                return true;
            }
        }
    }
    
    return false;
}

public function getTodayStatusAttribute()
{
    $today = Carbon::today();
    $now = Carbon::now();

    // Vérifie jour férié
    $holiday = $this->holidays()->whereDate('holiday_date', $today)->first();
    if ($holiday) {
        return $holiday->label ?? 'Fermeture (jour férié)';
    }

    // Vérifie exception
    $exception = $this->exceptions()->whereDate('exception_date', $today)->first();
    if ($exception) {
        return $exception->label 
            ? 'Fermeture exceptionnelle : ' . $exception->label 
            : 'Fermeture exceptionnelle';
    }

    // Vérifier l'horaire régulier pour aujourd'hui
    $dayOfWeek = strtolower($today->englishDayOfWeek);
    $regularSchedule = $this->schedules()
        ->where('day_of_week', $dayOfWeek)
        ->first();
    
    if ($regularSchedule && $regularSchedule->is_closed) {
        return 'Fermé';
    }

    // Cas normal - vérifier ouvert_jusqua
    if ($this->ouvert_jusqua) {
        $heureFermeture = Carbon::parse($this->ouvert_jusqua);
        if ($now->lt($heureFermeture)) {
            return 'Ouvert';
        }
    }

    // Vérifier les créneaux horaires
    if ($regularSchedule && !$regularSchedule->is_closed && isset($regularSchedule->time_slots) && is_array($regularSchedule->time_slots)) {
        foreach ($regularSchedule->time_slots as $slot) {
            $start = Carbon::parse($slot['start']);
            $end = Carbon::parse($slot['end']);
            
            if ($now->between($start, $end)) {
                return 'Ouvert';
            }
        }
    }

    return 'Fermé';
}


    /**
     * Relation avec les produits
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relation avec le personnel
     */
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

}
