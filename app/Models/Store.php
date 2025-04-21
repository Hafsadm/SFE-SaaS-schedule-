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
        'services'
    ];

    protected $casts = [
        'services' => 'array',
        'ouvert_jusqua' => 'datetime:H:i'
    ];


  public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Récupère l'horaire du jour actuel
   // Récupère l'horaire du jour actuel
   public function getTodaySchedule()
   {
       $today = strtolower(Carbon::now()->format('l'));
       
       // Si vous utilisez des entiers pour les jours de la semaine
       $dayMapping = [
           'monday' => 1,
           'tuesday' => 2,
           'wednesday' => 3,
           'thursday' => 4,
           'friday' => 5,
           'saturday' => 6,
           'sunday' => 7
       ];
       $todayNumber = $dayMapping[$today] ?? null;
       
       // Vérifier d'abord s'il y a une exception pour aujourd'hui
       $exceptionSchedule = $this->schedules()
           ->whereDate('exception_date', Carbon::today())
           ->first();
           
       if ($exceptionSchedule) {
           return $exceptionSchedule;
       }
       
       // Sinon, retourner l'horaire régulier
       return $this->schedules()
           ->where('day_of_week', $todayNumber ?? $today)
           ->first();
   }
   
   // Accesseur pour obtenir tous les horaires de la semaine formatés
   public function getFormattedWeeklyHoursAttribute()
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
       
       // Si vous utilisez des entiers pour les jours de la semaine
       $dayMapping = [
           'monday' => 1,
           'tuesday' => 2,
           'wednesday' => 3,
           'thursday' => 4,
           'friday' => 5,
           'saturday' => 6,
           'sunday' => 7
       ];
       
       $today = strtolower(Carbon::now()->format('l'));
       $html = '';
       
       // Vérifier s'il y a une exception pour aujourd'hui
       $exceptionToday = $this->schedules()
           ->whereDate('exception_date', Carbon::today())
           ->first();
           
       if ($exceptionToday) {
           $html .= '<div class="day-schedule today">';
           $html .= '<div class="day-name">Aujourd\'hui (' . $days[$today] . ')</div>';
           
           if ($exceptionToday->is_holiday) {
               $html .= '<div class="holiday-text">' . $exceptionToday->exception_reason . ' - Fermé</div>';
           } elseif ($exceptionToday->is_closed) {
               $html .= '<div class="closed-text">Fermé exceptionnellement</div>';
               if ($exceptionToday->exception_reason) {
                   $html .= '<div class="exception-reason">' . $exceptionToday->exception_reason . '</div>';
               }
           } else {
               if (isset($exceptionToday->time_slots) && is_array($exceptionToday->time_slots)) {
                   foreach ($exceptionToday->time_slots as $slot) {
                       $html .= '<div class="time-slot">' . 
                           date('H:i', strtotime($slot['start'])) . ' - ' . 
                           date('H:i', strtotime($slot['end'])) . 
                           '</div>';
                   }
               } else {
                   $html .= '<div class="no-hours">Aucun créneau défini</div>';
               }
               if ($exceptionToday->exception_reason) {
                   $html .= '<div class="exception-reason">' . $exceptionToday->exception_reason . '</div>';
               }
           }
           
           $html .= '</div>';
       }
       
       // Afficher les horaires réguliers pour tous les jours de la semaine
       foreach ($days as $dayKey => $dayName) {
           // Si c'est aujourd'hui et qu'il y a une exception, on l'a déjà affiché
           if ($dayKey === $today && $exceptionToday) {
               continue;
           }
           
           // Récupérer l'horaire pour ce jour
           $schedule = $this->schedules()
               ->where('day_of_week', isset($dayMapping) ? $dayMapping[$dayKey] : $dayKey)
               ->first();
               
           $isToday = ($dayKey === $today);
           
           $html .= '<div class="day-schedule' . ($isToday ? ' today' : '') . '">';
           $html .= '<div class="day-name">' . ($isToday ? 'Aujourd\'hui (' : '') . $dayName . ($isToday ? ')' : '') . '</div>';
           
           if ($schedule) {
               if ($schedule->is_closed) {
                   $html .= '<div class="closed-text">Fermé</div>';
               } else {
                   if (isset($schedule->time_slots) && is_array($schedule->time_slots)) {
                       foreach ($schedule->time_slots as $slot) {
                           $html .= '<div class="time-slot">' . 
                               date('H:i', strtotime($slot['start'])) . ' - ' . 
                               date('H:i', strtotime($slot['end'])) . 
                               '</div>';
                       }
                   } else {
                       $html .= '<div class="no-hours">Aucun créneau défini</div>';
                   }
               }
           } else {
               $html .= '<div class="no-hours">Horaires non définis</div>';
           }
           
           $html .= '</div>';
       }
       
       // Afficher les prochaines exceptions (jours fériés et autres exceptions)
       $futureExceptions = $this->schedules()
           ->whereNotNull('exception_date')
           ->whereDate('exception_date', '>', Carbon::today())
           ->orderBy('exception_date')
           ->limit(5)
           ->get();
           
       if ($futureExceptions->count() > 0) {
           $html .= '<div class="exceptions-section">';
           $html .= '<div class="exceptions-title">Prochaines exceptions</div>';
           
           foreach ($futureExceptions as $exception) {
               $html .= '<div class="exception-item">';
               $html .= '<div class="exception-date">' . $exception->exception_date->format('d/m/Y') . '</div>';
               
               if ($exception->is_holiday) {
                   $html .= '<div class="holiday-text">' . $exception->exception_reason . ' - Fermé</div>';
               } elseif ($exception->is_closed) {
                   $html .= '<div class="closed-text">Fermé</div>';
                   if ($exception->exception_reason) {
                       $html .= '<div class="exception-reason">' . $exception->exception_reason . '</div>';
                   }
               } else {
                   if (isset($exception->time_slots) && is_array($exception->time_slots)) {
                       foreach ($exception->time_slots as $slot) {
                           $html .= '<div class="time-slot">' . 
                               date('H:i', strtotime($slot['start'])) . ' - ' . 
                               date('H:i', strtotime($slot['end'])) . 
                               '</div>';
                       }
                   }
                   if ($exception->exception_reason) {
                       $html .= '<div class="exception-reason">' . $exception->exception_reason . '</div>';
                   }
               }
               
               $html .= '</div>';
           }
           
           $html .= '</div>';
       }
       
       return $html;
   }
   
   // Vérifie si le magasin est ouvert maintenant
   public function getIsOpenNowAttribute()
   {
       $schedule = $this->getTodaySchedule();
       
       if (!$schedule || $schedule->is_closed || $schedule->is_holiday) {
           return false;
       }
       
       $now = Carbon::now();
       $currentTime = $now->format('H:i');
       
       foreach ($schedule->time_slots as $slot) {
           if ($currentTime >= $slot['start'] && $currentTime <= $slot['end']) {
               return true;
           }
       }
       
       return false;
   }
}